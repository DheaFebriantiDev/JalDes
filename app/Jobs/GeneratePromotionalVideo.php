<?php

namespace App\Jobs;

use App\Models\DraftPrompt;
use App\Models\Wisata;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneratePromotionalVideo implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Wisata $wisata,
        public DraftPrompt $draft
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 1. Construct a clean prompt optimized for the Damo model
            $finalPrompt = "cinematic drone video of tourism spot, {$this->wisata->nama_wisata}, {$this->wisata->deskripsi}, {$this->draft->prompt_message}";

            Log::info('Starting Replicate AI Generation for Wisata ID: ' . $this->wisata->id);

            $token = config('services.replicate.key');
            if (! $token) {
                throw new \Exception('REPLICATE_API_TOKEN is missing.');
            }

            // We inject the owner and model name directly into the URL path
            // Format: /v1/models/{owner}/{model_name}/predictions

            // Example using the text-to-video model:
            $modelOwner = 'lucataco';
            $modelName = 'hotshot-xl';
            $endpoint = "https://api.replicate.com/v1/models/{$modelOwner}/{$modelName}/predictions";

            $response = Http::withToken($token)->post($endpoint, [
                'input' => [
                    'prompt' => $finalPrompt,
                    'mp4' => true, // Specific to video models to get MP4 instead of GIF
                ],
            ]);

            // If Replicate REST API returns 404 for community model path, resolve latest_version like the official SDK does
            if ($response->status() === 404) {
                $modelInfo = Http::withToken($token)->get("https://api.replicate.com/v1/models/{$modelOwner}/{$modelName}")->json();
                $versionId = $modelInfo['latest_version']['id'] ?? '78b3a6257e16e4b241245d65c8b2b81ea2e1ff7ed4c55306b511509ddbfd327a';

                $response = Http::withToken($token)->post('https://api.replicate.com/v1/predictions', [
                    'version' => $versionId,
                    'input' => [
                        'prompt' => $finalPrompt,
                        'mp4' => true,
                    ],
                ]);
            }

            // Handle Replicate free-tier 429 burst rate limit automatically
            if ($response->status() === 429) {
                $retryAfter = (int) ($response->json('retry_after') ?? 10);
                Log::warning("Replicate rate-limited (429). Waiting {$retryAfter}s before retrying...");
                if (! app()->runningUnitTests()) {
                    sleep($retryAfter + 1);
                }

                $response = Http::withToken($token)->post('https://api.replicate.com/v1/predictions', [
                    'version' => $versionId ?? '78b3a6257e16e4b241245d65c8b2b81ea2e1ff7ed4c55306b511509ddbfd327a',
                    'input' => [
                        'prompt' => $finalPrompt,
                        'mp4' => true,
                    ],
                ]);
            }

            if ($response->failed()) {
                throw new \Exception('Replicate Start Error: ' . $response->body());
            }

            $prediction = $response->json();
            $statusUrl = $prediction['urls']['get'] ?? null;

            if (! $statusUrl) {
                throw new \Exception('Failed to get polling URL from Replicate: ' . json_encode($prediction));
            }

            // 3. Polling Loop: Replicate processes video asynchronously
            $videoUrl = null;
            $attempts = 0;
            $maxAttempts = 60; // Max 4 minutes (60 * 4 seconds)

            while ($attempts < $maxAttempts) {
                if (! app()->runningUnitTests()) {
                    sleep(4);
                }
                $attempts++;

                $check = Http::withToken($token)->get($statusUrl)->json();
                $status = $check['status'] ?? 'unknown';

                Log::info("Replicate Status (Attempt {$attempts}): " . $status);

                if ($status === 'succeeded') {
                    // Output is usually a string (URL) for this specific model
                    $videoUrl = is_array($check['output']) ? $check['output'][0] : $check['output'];
                    break;
                } elseif ($status === 'failed' || $status === 'canceled') {
                    throw new \Exception('Replicate Generation Failed: ' . json_encode($check));
                }
            }

            if (! $videoUrl) {
                throw new \Exception('Replicate timeout after 4 minutes.');
            }

            // 4. Save the generated URL directly to the database
            $this->wisata->update([
                'video_promosi' => $videoUrl,
            ]);

            Log::info('HACKATHON SUCCESS! Saved Replicate Video URL: ' . $videoUrl);
        } catch (\Exception $e) {
            Log::error('AI Job Error: ' . $e->getMessage());
            $this->wisata->update(['status' => false]);
            throw $e;
        }
    }
}
