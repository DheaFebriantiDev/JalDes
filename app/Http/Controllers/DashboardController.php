<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\KepalaDesa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

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
}
