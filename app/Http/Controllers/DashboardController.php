<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\Wisata;
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
            $kades = $user->kepalaDesa;
            $desa = $kades?->desa;
            $wisataPending = $kades ? Wisata::where('nipd', $kades->nipd)->whereNull('status')->count() : 0;
            $wisataApproved = $kades ? Wisata::where('nipd', $kades->nipd)->where('status', true)->count() : 0;

            return view('dashboard.kd', [
                'user' => $user,
                'kepalaDesa' => $kades,
                'desa' => $desa,
                'wisataPending' => $wisataPending,
                'wisataApproved' => $wisataApproved,
            ]);
        }

        // 3. Role Agensi: Seluruh data user + agensi (beserta wisata & draft prompt) ditampilkan sebagai JSON
        $user->load(['agensi.wisata.desa', 'agensi.wisata.draftPrompt', 'agensi.draftPrompts']);
        $agensi = $user->agensi;
        $wisataList = $agensi?->wisata ?? collect();

        return view('dashboard.agensi', [
            'user' => $user,
            'agensi' => $agensi,
            'wisataList' => $wisataList,
            'totalWisata' => $wisataList->count(),
            'wisataPending' => $wisataList->whereNull('status')->count(),
            'wisataApproved' => $wisataList->where('status', true)->count(),
            'wisataRejected' => $wisataList->where('status', false)->count(),
            'json' => $user->toArray(),
        ]);
    }
}
