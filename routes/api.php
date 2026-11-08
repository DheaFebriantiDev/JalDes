<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\WisataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgensiRegisterController;
use App\Http\Controllers\Api\KadesController;
use App\Http\Controllers\Api\AdminRegisterController;

// Cek User
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ================= PUBLIK (Bisa diakses tanpa login) =================

// Katalog wisata publik
Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{wisata}', [WisataController::class, 'show']);

// Draft Prompt
Route::get('/draft-prompt', [DraftPromptController::class, 'index']);
Route::get('/draft-prompt/{draftPrompt}', [DraftPromptController::class, 'show']);
Route::post('/draft-prompt', [DraftPromptController::class, 'store']);

// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AgensiRegisterController::class, 'register']);
Route::post('/register-admin', [AdminRegisterController::class, 'register']);


// ================= AUTHENTICATED (Harus login, bebas role) =================
Route::middleware(['auth:sanctum'])->group(function () {
    // Ambil data agensi (Buat Admin & Kades)
    Route::get('/agensi', [AgensiController::class, 'index']);
    Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);

    // CRUD Wisata (Agensi & Admin)
    Route::apiResource('wisata', WisataController::class)->except(['index', 'show']);
    Route::post('/wisata/{wisata}/verifikasi', [WisataController::class, 'verifikasi']);
    
    // CRUD Draft Prompt
    Route::apiResource('draft-prompt', DraftPromptController::class)->except(['index', 'show']);
});


// ================= ADMIN ONLY (Khusus Admin) =================
Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::apiResource('desa', DesaController::class);
    Route::apiResource('kades', KadesController::class);
    
    // Admin cuma butuh bisa Hapus/Edit Agensi, jadi kita pisahin
    Route::put('/agensi/{agensi}', [AgensiController::class, 'update']);
    Route::delete('/agensi/{agensi}', [AgensiController::class, 'destroy']);
});