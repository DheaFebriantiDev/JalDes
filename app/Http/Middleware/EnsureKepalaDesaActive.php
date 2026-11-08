<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureKepalaDesaActive
{
    /**
     * Logout kepala desa yang sudah tidak menjabat (status = false) di setiap request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isKepalaDesa() && ! $user->kepalaDesa?->status) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $user->currentAccessToken()?->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Akun kepala desa tidak aktif (sudah tidak menjabat).',
                ], 403);
            }

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'login' => 'Akun kepala desa tidak aktif (sudah tidak menjabat).',
            ]);
        }

        return $next($request);
    }
}
