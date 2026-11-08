<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\KepalaDesaController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Registrasi Agensi Baru (Create Agensi dianggap register, dapat diakses guest)
Route::get('/register-agensi', [AgensiController::class, 'create'])->name('agensi.create');
Route::post('/register-agensi', [AgensiController::class, 'store'])->name('agensi.store');

Route::middleware(['auth', 'kd.active'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Modul Agensi (Index, Show, Edit, Update, Destroy)
    Route::resource('agensi', AgensiController::class)->except(['create', 'store']);

    // Modul Wisata & Sistem Verifikasi oleh Kepala Desa
    Route::resource('wisata', WisataController::class)->parameters(['wisata' => 'wisata']);
    Route::post('/wisata/{wisata}/verifikasi', [WisataController::class, 'verifikasi'])->name('wisata.verifikasi');

    // Modul Draft Prompt Promosi AI
    Route::resource('draft-prompt', DraftPromptController::class)->except(['edit', 'update']);
});

// Fitur CRUD Desa & Kepala Desa DIBATASI KHUSUS HANYA UNTUK ROLE ADMIN
Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::resource('desa', DesaController::class);
    Route::resource('kepala-desa', KepalaDesaController::class)->only(['index', 'create', 'store']);
    Route::get('/api/cek-kodepos/{kode_pos}', [KepalaDesaController::class, 'cekKodePos'])->name('api.cek-kodepos');
});

Route::get('/test-video-api', function () {
    $apiKey = config('services.imagegpt.key') ?: env('IMAGEGPT_API_KEY');

    if (empty($apiKey)) {
        return response()->json([
            'status' => 'error',
            'message' => 'IMAGEGPT_API_KEY belum disetel di file .env!',
        ], 400);
    }

    try {
        $endpoint = 'https://api.imagegpt.online/generate/text-video';

        $payload = [
            'prompt' => 'A scenic view of a tranquil Indonesian village with green rice terraces and mountain view, cinematic lighting, 4k',
            'model' => 'SEEDANCE-2.5',
            'aspect_ratio' => '16:9',
            'duration' => 4,
            'resolution' => '720p',
        ];

        $response = \Illuminate\Support\Facades\Http::timeout(60)
            ->withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($endpoint, $payload);

        return response()->json([
            'http_status' => $response->status(),
            'is_success' => $response->successful(),
            'api_response' => $response->json() ?? $response->body(),
            'api_key_preview' => substr($apiKey, 0, 6) . '...' . substr($apiKey, -4),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'exception',
            'error_message' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/test-video-status/{taskId}', function (string $taskId) {
    $apiKey = config('services.imagegpt.key') ?: env('IMAGEGPT_API_KEY');

    if (empty($apiKey)) {
        return response()->json([
            'status' => 'error',
            'message' => 'IMAGEGPT_API_KEY belum disetel di file .env!',
        ], 400);
    }

    try {
        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'x-api-key' => $apiKey,
            ])
            ->get("https://api.imagegpt.online/status/{$taskId}");

        return response()->json([
            'http_status' => $response->status(),
            'is_success' => $response->successful(),
            'api_response' => $response->json() ?? $response->body(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'exception',
            'error_message' => $e->getMessage(),
        ], 500);
    }
});

require __DIR__.'/auth.php';
