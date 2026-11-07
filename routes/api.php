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

/*
|--------------------------------------------------------------------------
| Publik (tanpa token)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

// Registrasi agensi baru
Route::post('/agensi', [AgensiController::class, 'store']);

// Katalog wisata publik
Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{wisata}', [WisataController::class, 'show']);

// Detail & daftar agensi
Route::get('/agensi', [AgensiController::class, 'index']);
Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Terautentikasi (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'kd.active'])->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', DashboardController::class);

    // Agensi (update & hapus)
    Route::match(['put', 'patch'], '/agensi/{agensi}', [AgensiController::class, 'update']);
    Route::delete('/agensi/{agensi}', [AgensiController::class, 'destroy']);

    // Wisata & verifikasi oleh Kepala Desa
    Route::post('/wisata', [WisataController::class, 'store']);
    Route::match(['put', 'patch'], '/wisata/{wisata}', [WisataController::class, 'update']);
    Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy']);
    Route::post('/wisata/{wisata}/verifikasi', [WisataController::class, 'verifikasi']);

    // Draft Prompt
    Route::apiResource('draft-prompt', DraftPromptController::class)->except(['update'])->names('api.draft-prompt');
});

/*
|--------------------------------------------------------------------------
| Khusus Admin: Desa & Kepala Desa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::apiResource('desa', DesaController::class)->names('api.desa');
    Route::get('/kepala-desa', [KepalaDesaController::class, 'index']);
    Route::post('/kepala-desa', [KepalaDesaController::class, 'store']);
    Route::get('/cek-kodepos/{kode_pos}', [KepalaDesaController::class, 'cekKodePos'])->name('api.kepala-desa.cek-kodepos');
});
