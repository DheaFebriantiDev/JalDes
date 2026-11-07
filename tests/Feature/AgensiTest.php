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
        'users' => [
            [
                'username' => 'owner_pesona',
                'email' => 'owner@pesona.com',
                'password' => 'secret123',
            ],
        ],
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

test('guest can register agensi with multiple users automatically', function () {
    $response = $this->post('/register-agensi', [
        'nama_agensi' => 'Nusantara Travel',
        'nomor_telepon' => '0819876543',
        'users' => [
            [
                'username' => 'nusantara_admin',
                'email' => 'admin@nusantara.com',
                'password' => 'secret123',
            ],
            [
                'username' => 'nusantara_staff1',
                'email' => 'staff1@nusantara.com',
                'password' => 'secret123',
            ],
            [
                'username' => 'nusantara_staff2',
                'email' => 'staff2@nusantara.com',
                'password' => 'secret123',
            ],
        ],
    ]);

    $response->assertRedirect(route('dashboard'));

    // Pastikan ketiga user tersimpan dengan role agensi
    expect(User::where('username', 'nusantara_admin')->first()?->role)->toBe('agensi');
    expect(User::where('username', 'nusantara_staff1')->first()?->role)->toBe('agensi');
    expect(User::where('username', 'nusantara_staff2')->first()?->role)->toBe('agensi');

    // User pertama adalah penanggung jawab agensi
    $primaryUser = User::where('username', 'nusantara_admin')->first();
    $agensi = Agensi::where('nama_agensi', 'Nusantara Travel')->first();
    expect($agensi->user_id)->toBe($primaryUser->id);
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

