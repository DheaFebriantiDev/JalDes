<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DraftPromptSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $messages = [
            'Buatkan video promosi suasana pagi di Floating Market.',
            'Buatkan video promosi pemandangan Kawah Putih.',
            'Buatkan video promosi aktivitas perahu di Situ Cileunca.',
        ];
        $wisata = ['Floating Market Lembang', 'Kawah Putih', 'Situ Cileunca'];

        foreach ($messages as $i => $message) {
            DB::table('draft_prompt')->updateOrInsert(['prompt_message' => $message], [
                'timestamp' => $now,
                'wisata_id' => DB::table('wisata')->where('nama_wisata', $wisata[$i])->value('id'),
                'agensi_id' => AgensiSeeder::IDS[$i],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
