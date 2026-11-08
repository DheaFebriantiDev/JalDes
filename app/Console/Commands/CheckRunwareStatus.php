<?php

namespace App\Console\Commands;

use App\Models\DraftPrompt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckRunwareStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:check-runware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll status video Runware AI dan unduh video MP4 jika selesai';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memeriksa antrean video Runware dengan status pending...');

        // Ambil semua data draft_prompt dengan job_status bernilai 'pending'
        $pendingDrafts = DraftPrompt::with('wisata')
            ->where('job_status', 'pending')
            ->whereNotNull('job_id')
            ->get();

        if ($pendingDrafts->isEmpty()) {
            $this->line('Tidak ada draft_prompt dengan status pending.');
            return Command::SUCCESS;
        }

        $apiKey = env('RUNWARE_API_KEY');

        if (empty($apiKey)) {
            $errorMsg = 'RUNWARE_API_KEY belum disetel di file .env.';
            $this->error($errorMsg);
            Log::error($errorMsg);
            return Command::FAILURE;
        }

        foreach ($pendingDrafts as $draft) {
            $this->line("Memeriksa job_id / taskUUID: {$draft->job_id} (Draft ID: {$draft->id})...");

            try {
                $payload = [
                    [
                        'taskType' => 'getResponse',
                        'taskUUID' => $draft->job_id,
                    ],
                ];

                $response = Http::withToken($apiKey)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post('https://api.runware.ai/v1', $payload);

                if ($response->failed()) {
                    Log::error("HTTP Error saat polling Runware untuk job {$draft->job_id}: " . $response->body());
                    $this->warn("HTTP error pada task {$draft->job_id}");
                    continue;
                }

                $json = $response->json();

                // Evaluasi apakah terdapat error pada response global
                if (isset($json['errors']) && !empty($json['errors'])) {
                    Log::error("Runware mengembalikan errors untuk job {$draft->job_id}: " . json_encode($json['errors']));
                    $draft->update(['job_status' => 'failed']);
                    $this->error("Task {$draft->job_id} gagal.");
                    continue;
                }

                // Evaluasi struktur array data[0] pada JSON respons
                $taskData = $json['data'][0] ?? null;

                if (!$taskData) {
                    $this->line("Task {$draft->job_id} masih dalam proses (belum ada data).");
                    continue;
                }

                // Jika ditemukan struktur error atau status failed pada item data
                $status = strtolower($taskData['status'] ?? '');
                $hasError = !empty($taskData['error']) || !empty($taskData['errors']) || $status === 'failed' || $status === 'error';

                if ($hasError) {
                    $errorMessage = $taskData['errorMessage'] ?? $taskData['error'] ?? json_encode($taskData);
                    Log::error("Runware task {$draft->job_id} gagal: {$errorMessage}", ['data' => $taskData]);
                    $draft->update(['job_status' => 'failed']);
                    $this->error("Task {$draft->job_id} berstatus gagal.");
                    continue;
                }

                // Jika terdapat key videoURL di dalamnya
                if (!empty($taskData['videoURL'])) {
                    $videoUrl = $taskData['videoURL'];
                    $this->info("Video siap diunduh untuk job {$draft->job_id}: {$videoUrl}");

                    // Unduh file MP4-nya menggunakan Http::get
                    $downloadResponse = Http::timeout(120)->get($videoUrl);

                    if ($downloadResponse->failed()) {
                        Log::error("Gagal mengunduh video MP4 dari {$videoUrl} untuk job {$draft->job_id}");
                        $this->error("Gagal mengunduh file video dari Runware.");
                        continue;
                    }

                    // Simpan permanen ke Storage::disk('public') pada direktori videos/
                    $filename = 'videos/' . Str::uuid() . '.mp4';
                    Storage::disk('public')->put($filename, $downloadResponse->body());

                    // Ambil URL path local storage
                    $localVideoUrl = Storage::disk('public')->url($filename);

                    // Update kolom video pada tabel wisata dengan URL path local storage Anda
                    // Update kolom status pada tabel wisata menjadi 'ready' (atau true untuk kompatibilitas boolean)
                    if ($draft->wisata) {
                        $draft->wisata->update([
                            'video' => $localVideoUrl,
                            'video_promosi' => $localVideoUrl,
                            'status' => true,
                        ]);
                    }

                    // Update job_status pada draft_prompt menjadi 'completed'
                    $draft->update([
                        'job_status' => 'completed',
                    ]);

                    $this->info("Sukses! Video job {$draft->job_id} disimpan di {$filename} dan wisata diperbarui.");
                } else {
                    $this->line("Task {$draft->job_id} masih diproses oleh Runware...");
                }
            } catch (\Exception $e) {
                Log::error("Exception saat polling task Runware {$draft->job_id}: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->error("Terjadi exception pada task {$draft->job_id}: " . $e->getMessage());
            }
        }

        $this->info('Pengecekan Runware selesai.');
        return Command::SUCCESS;
    }
}

