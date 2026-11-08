<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DraftPromptSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $prompts = [
            'Kawah Putih'               => 'Buatkan video promosi keindahan alam dan kawah vulkanik putih kehijauan di Kawah Putih.',
            'Ranca Upas'                => 'Buatkan video promosi suasana sejuk perkemahan dan penangkaran rusa di Ranca Upas.',
            'Gunung Tangkuban Parahu'   => 'Buatkan video promosi pemandangan kawah aktif Kawah Ratu Gunung Tangkuban Parahu.',
            'Dusun Bambu'               => 'Buatkan video promosi ekowisata alam pegunungan dan budaya Sunda di Dusun Bambu.',
        ];

        foreach ($prompts as $namaWisata => $message) {
            $wisata = DB::table('wisata')->where('nama_wisata', $namaWisata)->first();

            if ($wisata) {
                DB::table('draft_prompt')->updateOrInsert(['prompt_message' => $message], [
                    'timestamp'  => $now,
                    'wisata_id'  => $wisata->id,
                    'agensi_id'  => $wisata->agensi_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
