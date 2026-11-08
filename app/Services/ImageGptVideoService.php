<?php

namespace App\Services;

use App\Models\DraftPrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImageGptVideoService
{
    /**
     * Submit video generation job ke ImageGPT API secara asinkron.
     *
     * @param int|string $draftId
     * @return bool
     */
    public function submitVideoJob(int|string $draftId): bool
    {
        $draft = DraftPrompt::find($draftId);

        if (!$draft) {
            Log::error("ImageGptVideoService: DraftPrompt dengan ID {$draftId} tidak ditemukan.");
            return false;
        }

        $apiKey = config('services.imagegpt.key') ?: env('IMAGEGPT_API_KEY');

        if (empty($apiKey)) {
            Log::error('ImageGptVideoService: IMAGEGPT_API_KEY belum disetel di file .env.');
            return false;
        }

        $endpoint = 'https://api.imagegpt.online/generate/text-video';

        $payload = [
            'prompt' => $draft->prompt_message,
            'model' => 'SEEDANCE-2.5',
            'aspect_ratio' => '16:9',
            'duration' => 4,
            'resolution' => '720p',
        ];

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, $payload);

            if ($response->failed()) {
                Log::error("ImageGPT API error (HTTP {$response->status()}): {$response->body()}", [
                    'draft_id' => $draftId,
                    'endpoint' => $endpoint,
                ]);
                return false;
            }

            $responseData = $response->json();
            $taskId = $responseData['task_id']
                ?? $responseData['id']
                ?? $responseData['job_id']
                ?? $responseData['data']['task_id']
                ?? null;

            if (!$taskId) {
                Log::error('ImageGPT API response tidak mengandung task_id: ' . $response->body(), [
                    'draft_id' => $draftId,
                    'response' => $responseData,
                ]);
                return false;
            }

            $draft->update([
                'job_id' => (string) $taskId,
                'job_status' => 'pending',
            ]);

            Log::info("Video job ImageGPT berhasil disubmit untuk Draft ID: {$draftId} dengan Task ID: {$taskId}");

            return true;
        } catch (Throwable $e) {
            Log::error("Exception saat submit video job ke ImageGPT: {$e->getMessage()}", [
                'draft_id' => $draftId,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}

