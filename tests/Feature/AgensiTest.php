<?php

use App\Models\Agensi;
use App\Models\User;

test('guest can access agensi registration page', function () {
    $this->get('/register-agensi')->assertOk();
});

test('guest can register agensi with single user and is automatically logged in with role agensi', function () {
    $response = $this->post('/register-agensi', [
        'nama_agensi' => 'Pesona Alam Tour',
        'nomor_telepon' => '0812345678',
        'npwp' => '12.345.678.9-001.000',
        'deskripsi' => 'Agensi tur alam.',
        'username' => 'owner_pesona',
        'email' => 'owner@pesona.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $user = User::where('username', 'owner_pesona')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('agensi'); // Auto role agensi

    $agensi = Agensi::where('nama_agensi', 'Pesona Alam Tour')->first();
    expect($agensi)->not->toBeNull();
    expect($agensi->user_id)->toBe($user->id);

    // Auto login verifikasi
    $this->assertAuthenticatedAs($user);
});

test('authenticated user can view agensi list and search', function () {
    $user = User::factory()->create(['role' => 'agensi']);
    Agensi::create([
        'nama_agensi' => 'Ciwidey Adventure',
        'nomor_telepon' => '08123',
        'npwp' => '111',
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get('/agensi?search=Ciwidey');
    $response->assertOk()
        ->assertSee('Ciwidey Adventure');
});
