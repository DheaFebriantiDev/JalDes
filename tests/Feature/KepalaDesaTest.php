<?php

use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('non-admin cannot access create kepala desa form', function () {
    $agensi = User::factory()->create(['role' => 'agensi']);
    $this->actingAs($agensi)->get('/kepala-desa/create')->assertForbidden();
});

test('admin can access create kepala desa form', function () {
    $this->actingAs($this->admin)->get('/kepala-desa/create')->assertOk();
});

test('cek-kodepos endpoint returns village details when found', function () {
    $desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'kecamatan' => 'Lembang',
        'kota' => 'Bandung Barat', 'provinsi' => 'Jawa Barat', 'nipd' => null,
    ]);

    $response = $this->actingAs($this->admin)->getJson("/api/cek-kodepos/{$desa->kode_pos}");
    $response->assertOk()
        ->assertJson([
            'exists' => true,
            'desa' => [
                'kode_pos' => 40391,
                'nama_desa' => 'Cibodas',
                'kecamatan' => 'Lembang',
            ],
        ]);
});

test('cek-kodepos endpoint returns exists false when not found', function () {
    $response = $this->actingAs($this->admin)->getJson('/api/cek-kodepos/99999');
    $response->assertOk()
        ->assertJson([
            'exists' => false,
        ]);
});

test('when village already exists, admin only inputs kode_pos and kades personal data', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $oldKades = KepalaDesa::create([
        'nipd' => 'KD-LAMA', 'nik' => 'NIK-LAMA', 'nama' => 'Pak Kades Lama',
        'nomor_telepon' => '08111', 'status' => true, 'user_id' => $u1->id,
    ]);

    $desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'kecamatan' => 'Lembang',
        'kota' => 'Bandung Barat', 'provinsi' => 'Jawa Barat', 'nipd' => $oldKades->nipd,
    ]);

    // Admin hanya mengisi kode_pos dan data diri kades baru (tanpa nama_desa, kecamatan, dll)
    $response = $this->actingAs($this->admin)->post('/kepala-desa', [
        'kode_pos' => 40391,
        'nipd' => 'KD-BARU-01',
        'nik' => 'NIK-BARU-01',
        'nama' => 'Pak Kades Baru',
        'nomor_telepon' => '08222',
    ]);

    $response->assertRedirect(route('desa.index'));

    // Verifikasi algoritma:
    // 1. Kades lama harus dinonaktifkan
    expect($oldKades->fresh()->status)->toBeFalse();

    // 2. Kades baru harus dibuat dan aktif
    $newKades = KepalaDesa::where('nipd', 'KD-BARU-01')->first();
    expect($newKades)->not->toBeNull();
    expect($newKades->status)->toBeTrue();

    // 3. Desa menunjuk kades baru
    expect($desa->fresh()->nipd)->toBe('KD-BARU-01');
});

test('when village does not exist, admin provides both village and kades data', function () {
    $response = $this->actingAs($this->admin)->post('/kepala-desa', [
        'kode_pos' => 50005,
        'nama_desa' => 'Desa Karanganyar',
        'kecamatan' => 'Cililin',
        'kota' => 'Bandung Barat',
        'provinsi' => 'Jawa Barat',
        'nipd' => 'KD-CILILIN',
        'nik' => 'NIK-CILILIN',
        'nama' => 'Kades Cililin',
        'nomor_telepon' => '08333',
    ]);

    $response->assertRedirect(route('desa.index'));

    $desa = Desa::where('kode_pos', 50005)->first();
    expect($desa)->not->toBeNull();
    expect($desa->nama_desa)->toBe('Desa Karanganyar');
    expect($desa->nipd)->toBe('KD-CILILIN');

    $kades = KepalaDesa::where('nipd', 'KD-CILILIN')->first();
    expect($kades)->not->toBeNull();
    expect($kades->status)->toBeTrue();
});

