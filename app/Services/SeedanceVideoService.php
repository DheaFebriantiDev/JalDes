<?php

namespace App\Services;

use App\Models\DraftPrompt;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SeedanceVideoService
{
    /**
     * Kirim prompt ke API Seedance dan simpan job_id untuk dipolling nanti.
     */
    public function submitVideoJob(int|string $draftId): DraftPrompt
    {
        $draft = DraftPrompt::with('wisata')->findOrFail($draftId);

        $response = Http::withToken(config('services.aiport.key'))
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->post(config('services.aiport.base_url').'/video/generate', [
                'model' => 'seedance-2.0-fast',
                'prompt' => $draft->prompt_message,
            ]);

        if ($response->failed()) {
            $draft->update(['job_status' => 'failed']);

            throw new RuntimeException('Gagal submit job video: HTTP '.$response->status().' '.$response->body());
        }

        $jobId = $response->json('job_id');

        if (! $jobId) {
            $draft->update(['job_status' => 'failed']);

            throw new RuntimeException('Response API tidak berisi job_id: '.$response->body());
        }

        $draft->update([
            'job_id' => $jobId,
            'job_status' => 'pending',
        ]);

        return $draft;
    }
}

