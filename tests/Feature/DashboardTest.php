<?php

use App\Models\Agensi;
use App\Models\User;

test('admin sees dashboard administrasi with summary', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard Administrasi')
        ->assertSee('TOTAL DESA');
});

test('guests are redirected to login', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

test('kd sees halo pak kades', function () {
    $user = User::factory()->create(['role' => 'kd']);
    App\Models\KepalaDesa::create([
        'nipd' => '111', 'nik' => '222', 'nama' => 'Pak Kades',
        'nomor_telepon' => '0812', 'status' => true, 'user_id' => $user->id,
    ]);

    $this->actingAs($user)->get('/dashboard')
        ->assertOk()
        ->assertSee('Halo pak kades');
});

test('agensi sees username, agency name and json data', function () {
    $user = User::factory()->create(['role' => 'agensi']);
    Agensi::create([
        'nomor_telepon' => '0812',
        'nama_agensi' => 'Agensi Uji',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->get('/dashboard')
        ->assertOk()
        ->assertSee($user->username)
        ->assertSee('Agensi Uji')
        ->assertSee('nama_agensi');
});

test('kepala desa deactivated during session is logged out', function () {
    $user = User::factory()->create(['role' => 'kd']);
    App\Models\KepalaDesa::create([
        'nipd' => '111', 'nik' => '222', 'nama' => 'Pak Kades',
        'nomor_telepon' => '0812', 'status' => false, 'user_id' => $user->id,
    ]);

    $this->actingAs($user)->get('/dashboard')->assertRedirect(route('login'));

    $this->assertGuest();
});

