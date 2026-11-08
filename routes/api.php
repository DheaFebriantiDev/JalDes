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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ================= PUBLIK =================
// Endpoint API Agensi (Publik)
Route::get('/agensi', [AgensiController::class, 'index']);
Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);
Route::post('/agensi', [AgensiController::class, 'store']);

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

// ================= AUTHENTICATED (Agensi, Kades, Admin) =================
Route::middleware(['auth:sanctum'])->group(function () {
    // CRUD Wisata (PENTING: Dipindah ke sini biar Agensi bisa akses)
    Route::apiResource('wisata', WisataController::class)->except(['index', 'show']);
    
    // Route Verifikasi Wisata (Khusus Kades)
    Route::post('/wisata/{wisata}/verifikasi', [WisataController::class, 'verifikasi']);
    
    // CRUD Draft Prompt
    Route::apiResource('draft-prompt', DraftPromptController::class)->except(['index', 'show']);
});

// ================= ADMIN ONLY (Khusus Admin) =================
Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::apiResource('desa', DesaController::class);
    Route::apiResource('kades', KadesController::class);
    Route::apiResource('agensi', AgensiController::class)->except(['create', 'edit']);
});