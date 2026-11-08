<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Wisata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user() ?? $request->user('sanctum');
        $query = Wisata::query()->with(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']);

        if ($user) {
            if ($user->isAgensi() && $user->agensi) {
                $query->where('agensi_id', $user->agensi->id);
            } elseif ($user->isKepalaDesa() && $user->kepalaDesa) {
                $query->where('nipd', $user->kepalaDesa->nipd);
            }
        } elseif (! $request->has('all')) {
            $query->where('status', true);
        }

        if ($request->filled('status')) {
            $status = $request->query('status');
            if ($status === 'approved' || $status === '1') {
                $query->where('status', true);
            } elseif ($status === 'rejected' || $status === '0') {
                $query->where('status', false);
            } elseif ($status === 'pending') {
                $query->whereNull('status');
            }
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_wisata', 'like', "%{$search}%")
                    ->orWhere('jalan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('desa', function ($dq) use ($search) {
                        $dq->where('nama_desa', 'like', "%{$search}%")
                            ->orWhere('kecamatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('agensi', function ($aq) use ($search) {
                        $aq->where('nama_agensi', 'like', "%{$search}%");
                    });
            });
        }

        $wisataList = $query->latest()->get();

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'total' => $wisataList->count(),
                'data' => $wisataList,
            ]);
        }

        return view('wisata.index', [
            'wisataList' => $wisataList,
            'search' => $request->query('search'),
            'statusFilter' => $request->query('status'),
            'jsonData' => $wisataList->toArray(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan menambahkan objek wisata.');
        }
        $desaList = Desa::whereNotNull('nipd')->with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();
        return view('wisata.create', compact('desaList'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan menambahkan objek wisata.');
        }

        $agensiId = $user->isAgensi() ? $user->agensi?->id : $request->input('agensi_id');

        if (! $agensiId) {
            if ($this->wantsJson($request)) {
                return response()->json(['success' => false, 'message' => 'Data profil agensi Anda belum terdaftar.'], 422);
            }
            return back()->withErrors(['agensi_id' => 'Data profil agensi Anda belum terdaftar.']);
        }

        $validated = $request->validate([
            'nama_wisata' => ['required', 'string', 'max:255'],
            'jalan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'integer', 'exists:desa,kode_pos'],
            'video_promosi' => ['required', 'string', 'max:255'],
            'gambar' => ['required', 'string', 'max:255'],
            'video' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['nullable', 'string'],
        ]);

        $desa = Desa::findOrFail($validated['kode_pos']);
        if (! $desa->nipd) {
            if ($this->wantsJson($request)) {
                return response()->json(['success' => false, 'message' => 'Desa yang dipilih belum memiliki Kepala Desa aktif.'], 422);
            }
            return back()->withErrors(['kode_pos' => 'Desa yang dipilih belum memiliki Kepala Desa aktif.'])->withInput();
        }

        $wisata = Wisata::create([
            'nama_wisata' => $validated['nama_wisata'],
            'jalan' => $validated['jalan'],
            'video_promosi' => $validated['video_promosi'],
            'gambar' => $validated['gambar'],
            'video' => $validated['video'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori' => $validated['kategori'] ?? 'wisata',
            'status' => null,
            'kode_pos' => $desa->kode_pos,
            'agensi_id' => $agensiId,
            'nipd' => $desa->nipd,
        ]);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Objek wisata berhasil diajukan dan menunggu verifikasi Kepala Desa.',
                'data' => $wisata->load(['desa', 'agensi', 'kepalaDesa']),
            ], 201);
        }

        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil diajukan.');
    }

    public function show(Wisata $wisata, Request $request): View|JsonResponse
    {
        $wisata->load(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']);
        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'data' => $wisata]);
        }
        return view('wisata.show', ['wisata' => $wisata, 'jsonData' => $wisata->toArray()]);
    }

    public function edit(Wisata $wisata, Request $request): View
    {
        $user = $request->user();
        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit wisata ini.');
        }
        $desaList = Desa::whereNotNull('nipd')->with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();
        return view('wisata.edit', compact('wisata', 'desaList'));
    }

    public function update(Request $request, Wisata $wisata): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit wisata ini.');
        }

        $validated = $request->validate([
            'nama_wisata' => ['required', 'string', 'max:255'],
            'jalan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'integer', 'exists:desa,kode_pos'],
            'video_promosi' => ['required', 'string', 'max:255'],
            'gambar' => ['required', 'string', 'max:255'],
            'video' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['nullable', 'string'],
        ]);

        $desa = Desa::findOrFail($validated['kode_pos']);

        $wisata->update([
            'nama_wisata' => $validated['nama_wisata'],
            'jalan' => $validated['jalan'],
            'video_promosi' => $validated['video_promosi'],
            'gambar' => $validated['gambar'],
            'video' => $validated['video'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori' => $validated['kategori'] ?? 'wisata',
            'kode_pos' => $desa->kode_pos,
            'nipd' => $desa->nipd,
        ]);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Objek wisata berhasil diperbarui.',
                'data' => $wisata->fresh(['desa', 'agensi', 'kepalaDesa']),
            ]);
        }

        return redirect()->route('wisata.index')->with('success', 'Data wisata berhasil diperbarui.');
    }

    public function destroy(Wisata $wisata, Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus wisata ini.');
        }
        $wisata->delete();

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Objek wisata berhasil dihapus.']);
        }
        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil dihapus.');
    }

    public function verifikasi(Request $request, Wisata $wisata): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        if (! $user->isKepalaDesa() || ! $user->kepalaDesa || $user->kepalaDesa->nipd !== $wisata->nipd) {
            abort(403, 'Hanya Kepala Desa penanggung jawab wilayah wisata ini yang berhak melakukan verifikasi.');
        }

        $request->validate([
            'status' => ['required', 'in:approve,reject,pending'],
        ]);

        $newStatus = match ($request->input('status')) {
            'approve' => true,
            'reject' => false,
            'pending' => null,
        };

        $wisata->update(['status' => $newStatus]);

        $statusText = match ($newStatus) {
            true => 'disetujui (aktif dipromosikan)',
            false => 'ditolak',
            null => 'dikembalikan ke status pending',
        };

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => "Wisata berhasil {$statusText}.",
                'data' => $wisata->fresh(),
            ]);
        }

        return back()->with('success', "Objek wisata '{$wisata->nama_wisata}' berhasil {$statusText}.");
    }
}