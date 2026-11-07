<?php

namespace App\Console\Commands;

use App\Models\DraftPrompt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CheckVideoJobStatus extends Command
{
    protected $signature = 'video:check';

    protected $description = 'Cek status job video AI (Seedance) yang pending/processing dan simpan hasilnya';

    public function handle(): int
    {
        $drafts = DraftPrompt::with('wisata')
            ->whereIn('job_status', ['pending', 'processing'])
            ->whereNotNull('job_id')
            ->get();

        foreach ($drafts as $draft) {
            try {
                $this->processDraft($draft);
            } catch (Throwable $e) {
                // Biarkan status tetap, akan dicoba lagi pada siklus berikutnya.
                $this->error("Draft #{$draft->id}: {$e->getMessage()}");
            }
        }

        $this->info("Selesai memeriksa {$drafts->count()} job.");

        return self::SUCCESS;
    }

    private function processDraft(DraftPrompt $draft): void
    {
        $response = Http::withToken(config('services.aiport.key'))
            ->acceptJson()
            ->timeout(30)
            ->get(config('services.aiport.base_url').'/jobs/'.$draft->job_id);

        if ($response->failed()) {
            $this->warn("Draft #{$draft->id}: HTTP {$response->status()}");

            return;
        }

        $status = $response->json('status');

        if ($status === 'done') {
            $url = $response->json('result_urls.0');

            if (! $url) {
                throw new \RuntimeException('result_urls kosong.');
            }

            $video = Http::timeout(300)->get($url);

            if ($video->failed()) {
                throw new \RuntimeException("Gagal mengunduh video (HTTP {$video->status()}).");
            }

            $path = 'videos/'.Str::uuid().'.mp4';
            Storage::disk('public')->put($path, $video->body());

            if ($draft->wisata) {
                $draft->wisata->update([
                    'video' => Storage::url($path),
                    'status' => true, // status wisata bertipe boolean: true = selesai/dipublikasikan
                ]);
            }

            $draft->update(['job_status' => 'completed']);
            $this->info("Draft #{$draft->id}: selesai ({$path}).");
        } elseif ($status === 'failed') {
            $draft->update(['job_status' => 'failed']);
            $this->warn("Draft #{$draft->id}: gagal.");
        } elseif ($status === 'processing' && $draft->job_status !== 'processing') {
            $draft->update(['job_status' => 'processing']);
        }
    }
}
