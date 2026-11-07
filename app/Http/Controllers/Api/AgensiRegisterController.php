<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgensiRegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_agensi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'npwp' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'users' => 'required|array|min:1',
            'users.*.username' => 'required|string|max:255|unique:users,username',
            'users.*.email' => 'required|email|max:255|unique:users,email',
            'users.*.password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $mainUser = null;

            // 2. Looping untuk bikin User baru
            foreach ($request->users as $index => $userData) {
                $user = User::create([
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => 'agensi', // Otomatis set role sebagai agensi
                ]);

                // Simpan user pertama sebagai penanggung jawab utama
                if ($index === 0) {
                    $mainUser = $user;
                }
            }

            // 3. Bikin Profil Agensi (pakai user_id dari user pertama)
            DB::table('agensi')->insert([
                'id' => Str::uuid()->toString(),
                'user_id' => $mainUser->id,
                'nama_agensi' => $request->nama_agensi,
                'nomor_telepon' => $request->nomor_telepon,
                'npwp' => $request->npwp,
                'deskripsi' => $request->deskripsi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Agensi Berhasil! Silakan login.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}