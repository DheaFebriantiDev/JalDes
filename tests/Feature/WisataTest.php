<?php

use App\Models\Agensi;
use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\User;
use App\Models\Wisata;

beforeEach(function () {
    // Setup Kades & Desa
    $this->kdUser = User::factory()->create(['role' => 'kd']);
    $this->kades = KepalaDesa::create([
        'nipd' => 'KADES-LEMBANG', 'nik' => 'NIK-001', 'nama' => 'Pak Kades Lembang',
        'nomor_telepon' => '08123', 'status' => true, 'user_id' => $this->kdUser->id,
    ]);

    $this->desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'kecamatan' => 'Lembang',
        'kota' => 'Bandung Barat', 'provinsi' => 'Jawa Barat', 'nipd' => $this->kades->nipd,
    ]);

    // Setup Agensi
    $this->agensiUser = User::factory()->create(['role' => 'agensi']);
    $this->agensi = Agensi::create([
        'nomor_telepon' => '08999',
        'nama_agensi' => 'Lembang Tour',
        'user_id' => $this->agensiUser->id,
    ]);
});

test('agensi can create new wisata and kades is automatically assigned', function () {
    $response = $this->actingAs($this->agensiUser)->post('/wisata', [
        'nama_wisata' => 'Bukit Bintang Lembang',
        'jalan' => 'Jl. Bukit Bintang No. 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/bukit.mp4',
        'gambar' => 'images/wisata/bukit.jpg',
        'video' => 'videos/wisata/bukit.mp4',
        'deskripsi' => 'Pemandangan malam kota Bandung.',
    ]);

    $response->assertRedirect(route('wisata.index'));

    $wisata = Wisata::where('nama_wisata', 'Bukit Bintang Lembang')->first();
    expect($wisata)->not->toBeNull();
    expect($wisata->agensi_id)->toBe($this->agensi->id);
    expect($wisata->kode_pos)->toBe($this->desa->kode_pos);
    expect($wisata->nipd)->toBe($this->kades->nipd); // Kades otomatis ditunjuk
    expect($wisata->status)->toBeNull(); // Default pending verifikasi
});

test('kepala desa can verify and approve wisata in their village', function () {
    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Kades memverifikasi dan menyetujui wisata
    $response = $this->actingAs($this->kdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'approve',
    ]);

    $response->assertRedirect();
    expect($wisata->fresh()->status)->toBeTrue();
});

test('kepala desa can reject wisata in their village', function () {
    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Kades menolak wisata
    $response = $this->actingAs($this->kdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'reject',
    ]);

    $response->assertRedirect();
    expect($wisata->fresh()->status)->toBeFalse();
});

test('kades from another village cannot verify wisata outside their village', function () {
    $otherKdUser = User::factory()->create(['role' => 'kd']);
    $otherKades = KepalaDesa::create([
        'nipd' => 'KADES-OTHER', 'nik' => 'NIK-OTHER', 'nama' => 'Pak Kades Lain',
        'nomor_telepon' => '08777', 'status' => true, 'user_id' => $otherKdUser->id,
    ]);

    $wisata = Wisata::create([
        'nama_wisata' => 'Tangkuban Parahu',
        'jalan' => 'Jl. Tangkuban Parahu',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'videos/promosi/tangkuban.mp4',
        'gambar' => 'images/wisata/tangkuban.jpg',
        'video' => 'videos/wisata/tangkuban.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd, // Desa milik kades Lembang, bukan kades Lain
    ]);

    // Kades lain mencoba memverifikasi -> 403 Forbidden
    $this->actingAs($otherKdUser)->post("/wisata/{$wisata->id}/verifikasi", [
        'status' => 'approve',
    ])->assertForbidden();

    expect($wisata->fresh()->status)->toBeNull();
});

test('public api wisata only returns approved wisata by default', function () {
    // Wisata disetujui
    Wisata::create([
        'nama_wisata' => 'Wisata Disetujui',
        'jalan' => 'Jl. Wisata 1',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'v1.mp4', 'gambar' => 'g1.jpg', 'video' => 'v1.mp4',
        'status' => true,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    // Wisata pending
    Wisata::create([
        'nama_wisata' => 'Wisata Pending',
        'jalan' => 'Jl. Wisata 2',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'v2.mp4', 'gambar' => 'g2.jpg', 'video' => 'v2.mp4',
        'status' => null,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);

    $response = $this->getJson('/api/wisata');
    $response->assertOk();
    $data = $response->json('data');

    expect(count($data))->toBe(1);
    expect($data[0]['nama_wisata'])->toBe('Wisata Disetujui');
});

