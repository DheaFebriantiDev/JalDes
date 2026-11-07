<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\WisataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AgensiRegisterController;
use App\Http\Controllers\Api\KadesController; // <--- TAMBAH INI
use App\Http\Controllers\Api\AdminRegisterController; // <--- TAMBAH INI

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint API Agensi
Route::get('/agensi', [AgensiController::class, 'index']);
Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);
Route::post('/agensi', [AgensiController::class, 'store']);

// Endpoint API Publik / Frontend Katalog Wisata
Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{wisata}', [WisataController::class, 'show']);

// Endpoint API Draft Prompt
Route::get('/draft-prompt', [DraftPromptController::class, 'index']);
Route::get('/draft-prompt/{draftPrompt}', [DraftPromptController::class, 'show']);
Route::post('/draft-prompt', [DraftPromptController::class, 'store']);

// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AgensiRegisterController::class, 'register']);
Route::post('/register-admin', [AdminRegisterController::class, 'register']);

// ================= ADMIN ONLY (Pake Middleware role.admin) =================
Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    // Route yang udah lo bikin
    Route::apiResource('wisata', WisataController::class);
    Route::apiResource('draft-prompt', DraftPromptController::class);
    Route::apiResource('desa', DesaController::class);

    // TAMBAHIN ROUTE KADES DI SINI
    Route::apiResource('kades', KadesController::class);
    Route::apiResource('agensi', AgensiController::class)->except(['create', 'edit']);
});