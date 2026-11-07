<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgensiSeeder extends Seeder
{
    public const IDS = [
        'a1b2c3d4-0001-4000-8000-000000000001',
        'a1b2c3d4-0002-4000-8000-000000000002',
        'a1b2c3d4-0003-4000-8000-000000000003',
    ];

    public function run(): void
    {
        $now = now();
        $data = [
            ['agensi_budi', '081234567801', 'Lembang Tour Agency', 'Agensi wisata alam Lembang.', '01.234.567.8-401.000'],
            ['agensi_sari', '081234567802', 'Ciwidey Adventure', 'Agensi wisata petualangan Ciwidey.', '02.345.678.9-402.000'],
            ['agensi_andi', '081234567803', 'Pangalengan Trip', null, null],
        ];

        foreach ($data as $i => [$username, $telp, $nama, $deskripsi, $npwp]) {
            DB::table('agensi')->updateOrInsert(['id' => self::IDS[$i]], [
                'nomor_telepon' => $telp,
                'nama_agensi' => $nama,
                'deskripsi' => $deskripsi,
                'npwp' => $npwp,
                'user_id' => DB::table('users')->where('username', $username)->value('id'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
