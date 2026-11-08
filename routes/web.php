<?php

use App\Http\Controllers\AgensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DraftPromptController;
use App\Http\Controllers\KepalaDesaController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController

Route::redirect('/', '/login');

// Registrasi Agensi Baru (Create Agensi dianggap register, dapat diakses guest)
Route::get('/register-agensi', [AgensiController::class, 'create'])->name('agensi.create');
Route::post('/register-agensi', [AgensiController::class, 'store'])->name('agensi.store');

Route::middleware(['auth', 'kd.active'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Modul Agensi (Index, Show, Edit, Update, Destroy)
    Route::resource('agensi', AgensiController::class)->except(['create', 'store']);

    // Modul Wisata & Sistem Verifikasi oleh Kepala Desa
    Route::resource('wisata', WisataController::class);
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

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Tangkap Sisa Route untuk Vue SPA (Katalog Wisata Publik / Pengunjung)
|--------------------------------------------------------------------------
| Menggunakan ->where('any', '^(?!api|login|register|dashboard|agensi|wisata|draft-prompt|desa|kepala-desa).*$')
| memastikan route backend di atas TIDAK ikut terpotong oleh Vue Router.
*/
Route::get('{any}', function () {
    return view('index'); // Mengarah ke file resources/views/index.blade.php
})->where('any', '^(?!api|login|register|dashboard|agensi|wisata|draft-prompt|desa|kepala-desa).*$');