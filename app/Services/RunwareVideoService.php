<?php

namespace App\Services;

use App\Models\DraftPrompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RunwareVideoService
{
    /**
     * Submit video generation job to Runware API.
     */
    public function submitVideoJob(int|string $draftId): bool
    {
        $draft = DraftPrompt::find($draftId);

        if (!$draft) {
            Log::error("RunwareVideoService: DraftPrompt dengan ID {$draftId} tidak ditemukan.");
            return false;
        }

        $taskUUID = Str::uuid()->toString();

        $payload = [
            [
                'taskType' => 'videoInference',
                'taskUUID' => $taskUUID,
                'model' => 'minimax:h3@lite',
                'positivePrompt' => $draft->prompt_message,
                'duration' => 4,
                'width' => 1280,
                'height' => 720,
                'deliveryMethod' => 'async',
            ],
        ];

        try {
            $apiKey = env('RUNWARE_API_KEY');

            $response = Http::withToken($apiKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://api.runware.ai/v1', $payload);

            if ($response->successful()) {
                $draft->update([
                    'job_id' => $taskUUID,
                    'job_status' => 'pending',
                ]);
                return true;
            }

            Log::error('Runware API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            // Jika server Runware mati atau koneksi putus, akan ditangkap di sini
            Log::error('Koneksi Runware Terputus: ' . $e->getMessage());
            return false;
        }
    }
}

