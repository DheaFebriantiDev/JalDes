<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesaSeeder extends Seeder
{
    public const KODE_POS = [40391, 40973, 40378];

    public function run(): void
    {
        $now = now();
        $desaList = [
            [
                'kode_pos' => 40391,
                'nama_desa' => 'Cibodas',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Kabupaten Bandung Barat',
                'kecamatan' => 'Lembang',
                'nipd' => KepalaDesaSeeder::NIPDS[0],
            ],
            [
                'kode_pos' => 40973,
                'nama_desa' => 'Alamendah',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Kabupaten Bandung',
                'kecamatan' => 'Rancabali',
                'nipd' => KepalaDesaSeeder::NIPDS[1],
            ],
            [
                'kode_pos' => 40378,
                'nama_desa' => 'Warnasari',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Kabupaten Bandung',
                'kecamatan' => 'Pangalengan',
                'nipd' => KepalaDesaSeeder::NIPDS[2],
            ],
        ];

        foreach ($desaList as $data) {
            DB::table('desa')->updateOrInsert(
                ['kode_pos' => $data['kode_pos']],
                $data + ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
