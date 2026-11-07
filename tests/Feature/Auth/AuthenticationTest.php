<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $this->get('/login')->assertOk();
});

test('users can authenticate using username', function () {
    $user = User::factory()->create(['role' => 'agensi']);

    $response = $this->post('/login', [
        'login' => $user->username,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
});

test('users can authenticate using email', function () {
    $user = User::factory()->create(['role' => 'agensi']);

    $this->post('/login', [
        'login' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'login' => $user->username,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});

test('kepala desa with active status can login', function () {
    $user = User::factory()->create(['role' => 'kd']);
    App\Models\KepalaDesa::create([
        'nipd' => '111', 'nik' => '222', 'nama' => 'Pak Kades',
        'nomor_telepon' => '0812', 'status' => true, 'user_id' => $user->id,
    ]);

    $this->post('/login', ['login' => $user->username, 'password' => 'password'])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('kepala desa with inactive status cannot login', function () {
    $user = User::factory()->create(['role' => 'kd']);
    App\Models\KepalaDesa::create([
        'nipd' => '111', 'nik' => '222', 'nama' => 'Pak Kades',
        'nomor_telepon' => '0812', 'status' => false, 'user_id' => $user->id,
    ]);

    $this->post('/login', ['login' => $user->username, 'password' => 'password'])
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

