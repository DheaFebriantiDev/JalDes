<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login API: mengembalikan Bearer token Sanctum.
     * Login dapat memakai email atau username.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['login'])
            ->orWhere('username', $validated['login'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial tidak valid.',
            ], 401);
        }

        if ($user->isKepalaDesa() && ! $user->kepalaDesa?->status) {
            return response()->json([
                'success' => false,
                'message' => 'Akun kepala desa tidak aktif (sudah tidak menjabat).',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $user->createToken('api-token')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => $user,
            ],
        ]);
    }

    /**
     * Profil user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load(['agensi', 'kepalaDesa.desa']),
        ]);
    }

    /**
     * Logout API: mencabut token yang sedang dipakai.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}

