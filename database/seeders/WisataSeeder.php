<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $data = [
            ['Floating Market Lembang', 'Jl. Grand Hotel No. 33', 'Pasar terapung dengan kuliner khas Sunda.'],
            ['Kawah Putih', 'Jl. Raya Ciwidey - Patengan', 'Danau kawah vulkanik berwarna putih kehijauan.'],
            ['Situ Cileunca', 'Jl. Raya Pangalengan', null],
        ];

        foreach ($data as $i => [$nama, $jalan, $deskripsi]) {
            $slug = str($nama)->slug();
            DB::table('wisata')->updateOrInsert(['nama_wisata' => $nama], [
                'jalan' => $jalan,
                'video_promosi' => "videos/promosi/{$slug}.mp4",
                'gambar' => "images/wisata/{$slug}.jpg",
                'video' => "videos/wisata/{$slug}.mp4",
                'deskripsi' => $deskripsi,
                'kode_pos' => DesaSeeder::KODE_POS[$i],
                'status' => $i !== 2,
                'agensi_id' => AgensiSeeder::IDS[$i],
                'nipd' => KepalaDesaSeeder::NIPDS[$i],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}


