<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $password = Hash::make('password');

        $rows = [
            ['admin', 'admin@jaldes.id', 'admin'],
            ['agensi_budi', 'agensi.budi@example.com', 'agensi'],
            ['agensi_sari', 'agensi.sari@example.com', 'agensi'],
            ['agensi_andi', 'agensi.andi@example.com', 'agensi'],
            ['kd_ujang', 'kd.ujang@example.com', 'kd'],
            ['kd_asep', 'kd.asep@example.com', 'kd'],
            ['kd_dedi', 'kd.dedi@example.com', 'kd'],
        ];

        foreach ($rows as [$username, $email, $role]) {
            DB::table('users')->updateOrInsert(
                ['username' => $username],
                compact('email', 'role', 'password') + ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
