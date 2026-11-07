<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KepalaDesaSeeder extends Seeder
{
    public const NIPDS = ['3204010001', '3204010002', '3204010003'];

    public function run(): void
    {
        $now = now();
        $data = [
            ['kd_ujang', '3204011234560001', 'Ujang Sudrajat', '081300000001'],
            ['kd_asep', '3204011234560002', 'Asep Saepudin', '081300000002'],
            ['kd_dedi', '3204011234560003', 'Dedi Mulyadi', '081300000003'],
        ];

        foreach ($data as $i => [$username, $nik, $nama, $telp]) {
            DB::table('kepala_desa')->updateOrInsert(['nipd' => self::NIPDS[$i]], [
                'nik' => $nik,
                'nama' => $nama,
                'nomor_telepon' => $telp,
                'status' => true,
                'user_id' => DB::table('users')->where('username', $username)->value('id'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

