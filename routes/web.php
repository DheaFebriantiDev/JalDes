<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\KepalaDesaController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController as WebAuthController; // Beri alias jika dibutuhkan
use App\Http\Controllers\AuthController as ApiAuthController; // Beri alias khusus untuk API

Route::get('{any}', function () {
    return view('index');
})->where('any', '^(?!api).*$'); // Tangkap semua selain prefix /api untuk dilempar ke Vue Router

