<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\KepalaDesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|JsonResponse
    {
        $user = $request->user();

        if ($this->wantsJson($request)) {
            return $this->json($user);
        }

        // 1. Role Admin: Dikhususkan untuk keperluan administrasi desa dan kades menjabat
        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'user' => $user,
                'totalDesa' => Desa::count(),
                'totalKades' => KepalaDesa::count(),
                'kadesAktif' => KepalaDesa::where('status', true)->count(),
            ]);
        }

        // 2. Role Kepala Desa
        if ($user->isKepalaDesa()) {
            return view('dashboard.kd', [
                'user' => $user,
                'kepalaDesa' => $user->kepalaDesa,
            ]);
        }

        // 3. Role Agensi: Seluruh data user + agensi (beserta wisata & draft prompt) ditampilkan sebagai JSON
        $user->load('agensi.wisata', 'agensi.draftPrompts');

        return view('dashboard.agensi', [
            'user' => $user,
            'agensi' => $user->agensi,
            'json' => $user->toArray(),
        ]);
    }

    /**
     * Response JSON dashboard (API) sesuai role pengguna.
     */
    private function json($user): JsonResponse
    {
        if ($user->isAdmin()) {
            $data = [
                'role' => 'admin',
                'user' => $user,
                'total_desa' => Desa::count(),
                'total_kades' => KepalaDesa::count(),
                'kades_aktif' => KepalaDesa::where('status', true)->count(),
            ];
        } elseif ($user->isKepalaDesa()) {
            $data = [
                'role' => 'kd',
                'user' => $user,
                'kepala_desa' => $user->kepalaDesa?->load('desa'),
            ];
        } else {
            $user->load('agensi.wisata', 'agensi.draftPrompts');
            $data = [
                'role' => 'agensi',
                'user' => $user,
                'agensi' => $user->agensi,
            ];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }
}
