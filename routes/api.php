<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
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

// Endpoint Agensi & Wisata
Route::get('/agensi', [AgensiController::class, 'index']);
Route::get('/agensi/{agensi}', [AgensiController::class, 'show']);
Route::post('/agensi', [AgensiController::class, 'store']);

Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{wisata}', [WisataController::class, 'show']);

Route::get('/draft-prompt', [DraftPromptController::class, 'index']);
Route::get('/draft-prompt/{draftPrompt}', [DraftPromptController::class, 'show']);
Route::post('/draft-prompt', [DraftPromptController::class, 'store']);

Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::apiResource('desa', DesaController::class);
});