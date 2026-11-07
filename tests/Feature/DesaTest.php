<?php

use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\User;

beforeEach(function () {
    // User admin untuk menguji CRUD Desa
    $this->user = User::factory()->create(['role' => 'admin']);
});

test('non admin users cannot access desa crud', function () {
    $agensi = User::factory()->create(['role' => 'agensi']);
    $kdUser = User::factory()->create(['role' => 'kd']);
    KepalaDesa::create([
        'nipd' => 'KD-99', 'nik' => 'NIK-99', 'nama' => 'Pak Kades',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $kdUser->id,
    ]);

    // Role agensi dilarang mengakses desa
    $this->actingAs($agensi)->get('/desa')->assertForbidden();
    $this->actingAs($agensi)->post('/desa', [])->assertForbidden();

    // Role kd dilarang mengakses desa
    $this->actingAs($kdUser)->get('/desa')->assertForbidden();

    $desa = Desa::create([
        'kode_pos' => 99999, 'nama_desa' => 'Desa Uji', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Uji', 'nipd' => null,
    ]);
    $this->actingAs($kdUser)->delete("/desa/{$desa->kode_pos}")->assertForbidden();
});

test('guest is redirected to login when accessing desa index', function () {
    $this->get('/desa')->assertRedirect(route('login'));
});

test('desa list is sorted alphabetically by desa name then kades name', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $u2 = User::factory()->create(['role' => 'kd']);

    $kadesB = KepalaDesa::create([
        'nipd' => '1001', 'nik' => '2001', 'nama' => 'Budi',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);
    $kadesA = KepalaDesa::create([
        'nipd' => '1002', 'nik' => '2002', 'nama' => 'Andi',
        'nomor_telepon' => '082', 'status' => true, 'user_id' => $u2->id,
    ]);

    // Buat desa: C, A, B
    Desa::create([
        'kode_pos' => 101, 'nama_desa' => 'Cibodas', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Lembang', 'nipd' => $kadesB->nipd,
    ]);
    Desa::create([
        'kode_pos' => 102, 'nama_desa' => 'Alamendah', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Rancabali', 'nipd' => $kadesA->nipd,
    ]);
    Desa::create([
        'kode_pos' => 103, 'nama_desa' => 'Babakan', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Ciparay', 'nipd' => null,
    ]);

    $response = $this->actingAs($this->user)->get('/desa');

    $response->assertOk();
    $response->assertSeeInOrder(['Alamendah', 'Babakan', 'Cibodas']);
});

test('search filters desa by desa name, kecamatan, or kades name', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $kades = KepalaDesa::create([
        'nipd' => '1001', 'nik' => '2001', 'nama' => 'Ujang Sudrajat',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);

    Desa::create([
        'kode_pos' => 101, 'nama_desa' => 'Cibodas', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Lembang', 'nipd' => $kades->nipd,
    ]);
    Desa::create([
        'kode_pos' => 102, 'nama_desa' => 'Alamendah', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Rancabali', 'nipd' => null,
    ]);

    // Cari berdasarkan nama kades
    $response = $this->actingAs($this->user)->get('/desa?search=Ujang');
    $response->assertOk()
        ->assertSee('Cibodas')
        ->assertDontSee('Alamendah');

    // Cari berdasarkan kecamatan
    $responseKec = $this->actingAs($this->user)->get('/desa?search=Rancabali');
    $responseKec->assertOk()
        ->assertSee('Alamendah')
        ->assertDontSee('Cibodas');
});

test('desa index returns JSON when requested', function () {
    Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'provinsi' => 'Jawa Barat',
        'kota' => 'Bandung Barat', 'kecamatan' => 'Lembang', 'nipd' => null,
    ]);

    $response = $this->actingAs($this->user)->getJson('/desa');
    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'total',
            'data' => [
                '*' => ['kode_pos', 'nama_desa', 'kecamatan', 'kota', 'provinsi', 'nipd'],
            ],
        ]);
});

test('can create a new desa with a new kepala desa', function () {
    $response = $this->actingAs($this->user)->post('/desa', [
        'kode_pos' => 50001,
        'nama_desa' => 'Desa Sukamaju',
        'provinsi' => 'Jawa Barat',
        'kota' => 'Kabupaten Sukabumi',
        'kecamatan' => 'Cikole',
        'kades_nipd' => '99001',
        'kades_nik' => '320199001',
        'kades_nama' => 'Sulaeman',
        'kades_telepon' => '08991234',
    ]);

    $response->assertRedirect(route('desa.index'));

    $this->assertDatabaseHas('desa', [
        'kode_pos' => 50001,
        'nama_desa' => 'Desa Sukamaju',
        'nipd' => '99001',
    ]);

    $this->assertDatabaseHas('kepala_desa', [
        'nipd' => '99001',
        'nama' => 'Sulaeman',
        'status' => true,
    ]);

    $this->assertDatabaseHas('users', [
        'role' => 'kd',
    ]);
});

test('adding kades to existing desa via desa store does not require village data and replaces old kades', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $oldKades = KepalaDesa::create([
        'nipd' => 'KD-EXISTING-01', 'nik' => 'NIK-EX-01', 'nama' => 'Pak Kades Lama',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);

    $desa = Desa::create([
        'kode_pos' => 40391, 'nama_desa' => 'Cibodas', 'provinsi' => 'Jabar',
        'kota' => 'Bandung Barat', 'kecamatan' => 'Lembang', 'nipd' => $oldKades->nipd,
    ]);

    // Submit ke /desa hanya dengan kode_pos dan data kades baru (tanpa nama_desa, kecamatan, kota, provinsi)
    $response = $this->actingAs($this->user)->post('/desa', [
        'kode_pos' => 40391,
        'kades_nipd' => 'KD-REPLACE-02',
        'kades_nik' => 'NIK-REPLACE-02',
        'kades_nama' => 'Pak Kades Pengganti',
        'kades_telepon' => '08999',
    ]);

    $response->assertRedirect(route('desa.index'));

    // Kades lama harus dinonaktifkan
    expect($oldKades->fresh()->status)->toBeFalse();

    // Kades baru harus aktif
    $newKades = KepalaDesa::where('nipd', 'KD-REPLACE-02')->first();
    expect($newKades)->not->toBeNull();
    expect($newKades->status)->toBeTrue();

    // Desa mengarah ke kades baru
    expect($desa->fresh()->nipd)->toBe('KD-REPLACE-02');
});

test('replacing kepala desa deactivates old kades and activates new kades', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $u2 = User::factory()->create(['role' => 'kd']);

    // Kades Lama (sedang menjabat, status = true)
    $oldKades = KepalaDesa::create([
        'nipd' => 'KADES-01', 'nik' => 'NIK-01', 'nama' => 'Kades Lama',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);

    // Kades Baru (belum menjabat, status = false)
    $newKades = KepalaDesa::create([
        'nipd' => 'KADES-02', 'nik' => 'NIK-02', 'nama' => 'Kades Baru',
        'nomor_telepon' => '082', 'status' => false, 'user_id' => $u2->id,
    ]);

    $desa = Desa::create([
        'kode_pos' => 40111,
        'nama_desa' => 'Desa Margahayu',
        'provinsi' => 'Jawa Barat',
        'kota' => 'Bandung',
        'kecamatan' => 'Margahayu',
        'nipd' => $oldKades->nipd,
    ]);

    // Update desa dengan kades pengganti (KADES-02)
    $response = $this->actingAs($this->user)->put("/desa/{$desa->kode_pos}", [
        'nama_desa' => 'Desa Margahayu',
        'provinsi' => 'Jawa Barat',
        'kota' => 'Bandung',
        'kecamatan' => 'Margahayu',
        'nipd' => $newKades->nipd,
    ]);

    $response->assertRedirect(route('desa.index'));

    // Verifikasi algoritma:
    // 1. Kades lama harus dinonaktifkan (status = false)
    expect($oldKades->fresh()->status)->toBeFalse();

    // 2. Kades baru harus aktif (status = true)
    expect($newKades->fresh()->status)->toBeTrue();

    // 3. Desa menunjuk kades baru
    expect($desa->fresh()->nipd)->toBe($newKades->nipd);
});

test('replacing kepala desa with direct new kades registration works and deactivates old kades', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $oldKades = KepalaDesa::create([
        'nipd' => 'KADES-OLD', 'nik' => 'NIK-OLD', 'nama' => 'Kades Senior',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);

    $desa = Desa::create([
        'kode_pos' => 40222,
        'nama_desa' => 'Desa Cihanjuang',
        'provinsi' => 'Jawa Barat',
        'kota' => 'Bandung Barat',
        'kecamatan' => 'Parongpong',
        'nipd' => $oldKades->nipd,
    ]);

    $response = $this->actingAs($this->user)->put("/desa/{$desa->kode_pos}", [
        'nama_desa' => 'Desa Cihanjuang',
        'provinsi' => 'Jawa Barat',
        'kota' => 'Bandung Barat',
        'kecamatan' => 'Parongpong',
        'new_kades_nipd' => 'KADES-BARU-01',
        'new_kades_nik' => 'NIK-BARU-01',
        'new_kades_nama' => 'Pak Kades Baru',
        'new_kades_telepon' => '08889999',
    ]);

    $response->assertRedirect(route('desa.index'));

    // Kades lama harus status = false
    expect($oldKades->fresh()->status)->toBeFalse();

    // Kades baru harus tersimpan dan status = true
    $kadesBaru = KepalaDesa::where('nipd', 'KADES-BARU-01')->first();
    expect($kadesBaru)->not->toBeNull();
    expect($kadesBaru->status)->toBeTrue();
    expect($desa->fresh()->nipd)->toBe('KADES-BARU-01');
});

test('destroy desa deactivates assigned kades', function () {
    $u1 = User::factory()->create(['role' => 'kd']);
    $kades = KepalaDesa::create([
        'nipd' => '1001', 'nik' => '2001', 'nama' => 'Pak Kades',
        'nomor_telepon' => '081', 'status' => true, 'user_id' => $u1->id,
    ]);

    $desa = Desa::create([
        'kode_pos' => 101, 'nama_desa' => 'Cibodas', 'provinsi' => 'Jabar',
        'kota' => 'Bandung', 'kecamatan' => 'Lembang', 'nipd' => $kades->nipd,
    ]);

    $this->actingAs($this->user)->delete("/desa/{$desa->kode_pos}")
        ->assertRedirect(route('desa.index'));

    $this->assertDatabaseMissing('desa', ['kode_pos' => 101]);
    expect($kades->fresh()->status)->toBeFalse();
});

