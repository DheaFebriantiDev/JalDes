<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\KepalaDesaController;
use App\Http\Controllers\WisataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint Public API Auth
Route::post('/login', [AuthController::class, 'login']);

// Endpoint Protected API Auth (Membutuhkan Token Bearer)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Autentikasi API (token Sanctum)
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Endpoint API Agensi (publik: daftar, lihat, registrasi)
Route::get('/agensi', [AgensiController::class, 'index']);
Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);
Route::post('/agensi', [AgensiController::class, 'store']);

Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{wisata}', [WisataController::class, 'show']);

Route::get('/draft-prompt', [DraftPromptController::class, 'index']);
Route::get('/draft-prompt/{draftPrompt}', [DraftPromptController::class, 'show']);
Route::post('/draft-prompt', [DraftPromptController::class, 'store']);

// Endpoint terproteksi login (setara route web group auth + kd.active)
Route::middleware(['auth:sanctum', 'kd.active'])->group(function () {
    Route::get('/dashboard', DashboardController::class);

    // Modul Agensi
    Route::match(['put', 'patch'], '/agensi/{agensi}', [AgensiController::class, 'update']);
    Route::delete('/agensi/{agensi}', [AgensiController::class, 'destroy']);

    // Modul Wisata & verifikasi Kepala Desa
    Route::post('/wisata', [WisataController::class, 'store']);
    Route::match(['put', 'patch'], '/wisata/{wisata}', [WisataController::class, 'update']);
    Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy']);
    Route::post('/wisata/{wisata}/verifikasi', [WisataController::class, 'verifikasi']);

    // Modul Draft Prompt
    Route::delete('/draft-prompt/{draftPrompt}', [DraftPromptController::class, 'destroy']);
});

// Endpoint API Desa & Kepala Desa: dilindungi permission admin
Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::apiResource('desa', DesaController::class);
    Route::apiResource('kepala-desa', KepalaDesaController::class)->only(['index', 'store']);
    Route::get('/cek-kodepos/{kode_pos}', [KepalaDesaController::class, 'cekKodePos']);
});