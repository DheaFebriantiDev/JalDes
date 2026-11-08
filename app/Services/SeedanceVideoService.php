<?php

namespace App\Services;

use App\Models\DraftPrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SeedanceVideoService
{
    /**
     * Submit video generation job to AIPORT / Runware API.
     */
    public function submitVideoJob(int|string $draftId): ?DraftPrompt
    {
        try {
            $draft = DraftPrompt::findOrFail($draftId);

            // Mendukung AIPORT_API_KEY atau RUNWARE_API_KEY
            $apiKey = env('AIPORT_API_KEY') ?: env('RUNWARE_API_KEY', 'dzUZvw9RDBJVpGpL78Zg5BrxhTirtlHk');

            if (empty($apiKey)) {
                throw new \Exception('API Key tidak ditemukan. Pastikan AIPORT_API_KEY atau RUNWARE_API_KEY terpasang di file .env.');
            }

            $endpoint = 'https://api.aiport.id/v1/video/generate';

            // Payload dengan model minimax:h3@lite (durasi 4 detik, resolusi 480p)
            $payload = [
                'model' => 'minimax:h3@lite',
                'prompt' => $draft->prompt_message,
                'resolution' => '480p',
                'duration' => 4,
                'ratio' => '16:9',
                'taskType' => 'videoInference',
                'taskUUID' => (string) Str::uuid(),
                'numberResults' => 1,
                'outputType' => 'URL',
                'outputFormat' => 'MP4',
                'outputQuality' => 95,
                'deliveryMethod' => 'async',
            ];

            Log::info("Mengirim video job untuk Draft ID: {$draftId}", [
                'endpoint' => $endpoint,
                'payload' => $payload,
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->failed()) {
                Log::error("Gagal submit video job (HTTP {$response->status()}): {$response->body()}", [
                    'draft_id' => $draftId,
                ]);
                $draft->update(['job_status' => 'failed']);
                return null;
            }

            $data = $response->json();
            $jobId = $data['job_id'] ?? $data['taskUUID'] ?? $data['id'] ?? null;

            if (!$jobId) {
                Log::error("Respon API tidak mengembalikan job_id: " . json_encode($data), [
                    'draft_id' => $draftId,
                ]);
                $draft->update(['job_status' => 'failed']);
                return null;
            }

            $draft->update([
                'job_id' => (string) $jobId,
                'job_status' => 'pending',
            ]);

            Log::info("Video job berhasil disubmit untuk Draft ID: {$draftId} dengan Job ID: {$jobId}");

            return $draft;
        } catch (\Throwable $e) {
            Log::error("Exception pada submitVideoJob: {$e->getMessage()}", [
                'draft_id' => $draftId,
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($draft) && $draft instanceof DraftPrompt) {
                $draft->update(['job_status' => 'failed']);
            }

            return null;
        }
    }
}

