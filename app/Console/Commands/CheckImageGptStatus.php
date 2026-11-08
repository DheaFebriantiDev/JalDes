<?php

namespace App\Console\Commands;

use App\Models\DraftPrompt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CheckImageGptStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll status video ImageGPT API dan unduh video MP4 jika selesai';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Memeriksa antrean video ImageGPT dengan status pending...');

        // Ambil semua data draft_prompt dengan job_status bernilai 'pending'
        $pendingDrafts = DraftPrompt::with('wisata')
            ->where('job_status', 'pending')
            ->whereNotNull('job_id')
            ->get();

        if ($pendingDrafts->isEmpty()) {
            $this->line('Tidak ada draft_prompt dengan status pending.');
            return Command::SUCCESS;
        }

        $apiKey = config('services.imagegpt.key') ?: env('IMAGEGPT_API_KEY');

        if (empty($apiKey)) {
            $errorMsg = 'IMAGEGPT_API_KEY belum dikonfigurasi di file .env.';
            $this->error($errorMsg);
            Log::error($errorMsg);
            return Command::FAILURE;
        }

        foreach ($pendingDrafts as $draft) {
            $this->line("Memeriksa Job ID: {$draft->job_id} (Draft ID: {$draft->id})...");

            // Sangat Penting: try-catch diletakkan di dalam looping untuk isolasi per-tugas
            try {
                $statusEndpoint = "https://api.imagegpt.online/status/{$draft->job_id}";

                $response = Http::timeout(30)
                    ->withHeaders([
                        'x-api-key' => $apiKey,
                    ])
                    ->get($statusEndpoint);

                if ($response->failed()) {
                    Log::error("HTTP Error saat polling ImageGPT untuk Job ID {$draft->job_id} (HTTP {$response->status()}): " . $response->body(), [
                        'draft_id' => $draft->id,
                        'job_id' => $draft->job_id,
                    ]);
                    $this->warn("HTTP error pada Job ID {$draft->job_id}");
                    continue;
                }

                $json = $response->json();

                // Evaluasi status tugas
                $status = strtolower(
                    $json['status']
                    ?? $json['data']['status']
                    ?? $json['task_status']
                    ?? $json['state']
                    ?? ''
                );

                // Cek kemungkinan error atau status failed
                $isFailed = in_array($status, ['failed', 'error', 'fail', 'cancelled', 'canceled'], true)
                    || !empty($json['error'])
                    || !empty($json['errors']);

                if ($isFailed) {
                    $errorMessage = $json['error']
                        ?? $json['message']
                        ?? $json['errorMessage']
                        ?? json_encode($json);

                    Log::error("ImageGPT task {$draft->job_id} gagal: {$errorMessage}", [
                        'draft_id' => $draft->id,
                        'response' => $json,
                    ]);

                    $draft->update(['job_status' => 'failed']);
                    $this->error("Task {$draft->job_id} berstatus gagal.");
                    continue;
                }

                // Ambil URL video dari berbagai struktur respon yang didukung
                $videoUrl = $json['video_url']
                    ?? $json['video']
                    ?? $json['url']
                    ?? $json['output']
                    ?? $json['data']['video_url']
                    ?? $json['data']['url']
                    ?? $json['data']['video']
                    ?? null;

                if (is_array($videoUrl)) {
                    $videoUrl = $videoUrl[0] ?? null;
                }

                $isCompleted = in_array($status, ['done', 'success', 'completed', 'succeeded'], true);

                // Jika status menunjukkan selesai (done/success) dan URL video tersedia
                if (($isCompleted || !empty($videoUrl)) && !empty($videoUrl) && $status !== 'processing' && $status !== 'pending') {
                    $this->info("Video siap diunduh untuk Job ID {$draft->job_id}: {$videoUrl}");

                    // Unduh file MP4 menggunakan Http::get dengan timeout yang memadai
                    $videoBinary = null;
                    $downloadResponse = Http::timeout(120)->get($videoUrl);

                    if ($downloadResponse->successful()) {
                        $videoBinary = $downloadResponse->body();
                    } else {
                        // Fallback ke file_get_contents jika Http::get gagal
                        $videoBinary = @file_get_contents($videoUrl);
                    }

                    if (empty($videoBinary)) {
                        Log::error("Gagal mengunduh file video dari {$videoUrl} untuk Job ID {$draft->job_id}");
                        $this->error("Gagal mengunduh file video dari ImageGPT.");
                        continue;
                    }

                    // Simpan fisik file ke Storage::disk('public') pada direktori videos/
                    $filename = 'videos/' . Str::uuid() . '.mp4';
                    Storage::disk('public')->put($filename, $videoBinary);

                    // Ambil URL path local storage
                    $localVideoUrl = Storage::disk('public')->url($filename);

                    // Update kolom video dan status pada tabel wisata
                    if ($draft->wisata) {
                        $draft->wisata->update([
                            'video' => $localVideoUrl,
                            'video_promosi' => $localVideoUrl,
                            'status' => 'ready',
                        ]);
                    }

                    // Update job_status pada draft_prompt menjadi 'completed'
                    $draft->update([
                        'job_status' => 'completed',
                    ]);

                    $this->info("Sukses! Video Job ID {$draft->job_id} disimpan di {$filename} dan wisata diperbarui.");
                } else {
                    $this->line("Job ID {$draft->job_id} masih diproses oleh ImageGPT (status: " . ($status ?: 'in-progress') . ")...");
                }
            } catch (Throwable $e) {
                // Mencegah satu tugas yang error/timeout menghentikan pengecekan tugas lainnya
                Log::error("Exception saat polling task ImageGPT {$draft->job_id}: " . $e->getMessage(), [
                    'draft_id' => $draft->id,
                    'job_id' => $draft->job_id,
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->error("Terjadi exception pada Job ID {$draft->job_id}: " . $e->getMessage());
            }
        }

        $this->info('Pengecekan ImageGPT selesai.');
        return Command::SUCCESS;
    }
}

