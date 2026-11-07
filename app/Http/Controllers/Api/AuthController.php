<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 3. Cek User & Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 4. Bikin Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Ambil Nama Tampilan
        $namaTampilan = $user->username; // Default pakai username
        
        try {
            // Cek role dengan aman (pake try-catch biar ga error kalau relasi ga ada)
            if ($user->role === 'agensi' && $user->agensi) {
                $namaTampilan = $user->agensi->nama_agensi;
            } elseif ($user->role === 'kd' && $user->kepalaDesa) {
                $namaTampilan = $user->kepalaDesa->nama;
            }
            // Admin cukup pake username
        } catch (\Exception $e) {
            // Kalau relasinya error / belum dimigrasi, abaikan, tetep pake username
            $namaTampilan = $user->username;
        }

        // 6. Kirim Response ke Vue
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'username' => $user->username,
                'nama_tampilan' => $namaTampilan,
                'email' => $user->email,
                'role' => $user->role, // 'admin', 'agensi', atau 'kd'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}