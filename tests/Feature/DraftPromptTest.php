<?php

use App\Models\Agensi;
use App\Models\Desa;
use App\Models\DraftPrompt;
use App\Models\KepalaDesa;
use App\Models\User;
use App\Models\Wisata;

beforeEach(function () {
    // Setup Kades & Desa
    $this->kdUser = User::factory()->create(['role' => 'kd']);
    $this->kades = KepalaDesa::create([
        'nipd' => 'KADES-01', 'nik' => 'NIK-001', 'nama' => 'Pak Kades',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $this->kdUser->id,
    ]);
    $this->desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'kecamatan' => 'Lembang',
        'kota' => 'Bandung Barat', 'provinsi' => 'Jawa Barat', 'nipd' => $this->kades->nipd,
    ]);

    // Setup Agensi & Wisata
    $this->agensiUser = User::factory()->create(['role' => 'agensi']);
    $this->agensi = Agensi::create([
        'nomor_telepon' => '08999',
        'nama_agensi' => 'Lembang Tour',
        'user_id' => $this->agensiUser->id,
    ]);

    $this->wisata = Wisata::create([
        'nama_wisata' => 'Floating Market',
        'jalan' => 'Jl. Grand Hotel',
        'kode_pos' => $this->desa->kode_pos,
        'video_promosi' => 'promosi.mp4',
        'gambar' => 'gambar.jpg',
        'video' => 'video.mp4',
        'status' => true,
        'agensi_id' => $this->agensi->id,
        'nipd' => $this->kades->nipd,
    ]);
});

test('agensi can create draft prompt for their wisata', function () {
    $response = $this->actingAs($this->agensiUser)->post('/draft-prompt', [
        'wisata_id' => $this->wisata->id,
        'prompt_message' => 'Buatkan video promosi pasar apung suasana senja dengan drone shot.',
    ]);

    $response->assertRedirect(route('draft-prompt.index'));

    $this->assertDatabaseHas('draft_prompt', [
        'wisata_id' => $this->wisata->id,
        'prompt_message' => 'Buatkan video promosi pasar apung suasana senja dengan drone shot.',
        'agensi_id' => $this->agensi->id,
    ]);
});

test('cannot create duplicate draft prompt for same wisata', function () {
    DraftPrompt::create([
        'timestamp' => now(),
        'prompt_message' => 'Prompt awal',
        'wisata_id' => $this->wisata->id,
        'agensi_id' => $this->agensi->id,
    ]);

    // Coba buat lagi untuk wisata yang sama -> validasi unique gagal
    $response = $this->actingAs($this->agensiUser)->post('/draft-prompt', [
        'wisata_id' => $this->wisata->id,
        'prompt_message' => 'Prompt kedua',
    ]);

    $response->assertSessionHasErrors('wisata_id');
});

test('non-agensi user cannot create draft prompt', function () {
    $this->actingAs($this->kdUser)->get('/draft-prompt/create')->assertForbidden();
    $this->actingAs($this->kdUser)->post('/draft-prompt', [
        'wisata_id' => $this->wisata->id,
        'prompt_message' => 'Prompt kades',
    ])->assertForbidden();
});

test('agensi can delete their draft prompt', function () {
    $draft = DraftPrompt::create([
        'timestamp' => now(),
        'prompt_message' => 'Prompt awal',
        'wisata_id' => $this->wisata->id,
        'agensi_id' => $this->agensi->id,
    ]);

    $response = $this->actingAs($this->agensiUser)->delete("/draft-prompt/{$draft->id}");
    $response->assertRedirect(route('draft-prompt.index'));

    $this->assertDatabaseMissing('draft_prompt', ['id' => $draft->id]);
});

