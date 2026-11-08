<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePromotionalVideo;
use App\Models\Desa;
use App\Models\DraftPrompt;
use App\Models\Wisata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WisataController extends Controller
{
    /**
     * Menampilkan daftar wisata.
     * Disesuaikan berdasarkan role pengguna:
     * - Agensi: Hanya melihat wisata miliknya.
     * - Kepala Desa: Melihat wisata yang masuk untuk diverifikasi di wilayah desanya.
     * - Admin / Publik: Melihat semua wisata atau yang sudah terverifikasi.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();
        $query = Wisata::query()->with(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']);

        // Filter berdasarkan role user
        if ($user) {
            if ($user->isAgensi() && $user->agensi) {
                $query->where('agensi_id', $user->agensi->id);
            } elseif ($user->isKepalaDesa() && $user->kepalaDesa) {
                $query->where('nipd', $user->kepalaDesa->nipd);
            }
        } elseif (! $request->has('all')) {
            // Pengunjung umum hanya melihat wisata yang sudah disetujui
            $query->where('status', true);
        }

        // Filter status
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

        // Filter search (nama wisata, jalan, kecamatan, desa, nama agensi)
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

        if ($request->wantsJson() || $request->query('format') === 'json') {
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

    /**
     * Form tambah wisata baru (hanya untuk agensi).
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan menambahkan objek wisata.');
        }

        // Ambil desa yang sudah memiliki kepala desa aktif
        $desaList = Desa::whereNotNull('nipd')->with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();

        return view('wisata.create', compact('desaList'));
    }

    /**
     * Simpan objek wisata baru.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan menambahkan objek wisata.');
        }

        $agensiId = $user->isAgensi() ? $user->agensi?->id : $request->input('agensi_id');

        $isJson = $request->wantsJson() || $request->query('format') === 'json' || $request->is('api/*');

        if (! $agensiId) {
            if ($isJson) {
                return response()->json(['success' => false, 'message' => 'Data profil agensi Anda belum terdaftar.'], 422);
            }

            return back()->withErrors(['agensi_id' => 'Data profil agensi Anda belum terdaftar.']);
        }

        $pilihanPromosi = $request->input('pilihan_promosi', $request->input('mode_promosi', 'ai'));

        $validated = $request->validate([
            'nama_wisata' => ['required', 'string', 'max:255'],
            'jalan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'integer', 'exists:desa,kode_pos'],
            'gambar' => ['required'],
            'video' => ['nullable'],
            'video_manual' => ['nullable'],
            'video_promosi' => ['nullable', 'string', 'max:255'],
            'video_promosi_manual' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'pilihan_promosi' => ['nullable', 'in:ai,manual'],
            'mode_promosi' => ['nullable', 'in:ai,manual'],
            'prompt_keterangan' => ['nullable', 'string'],
        ]);

        // Ambil data desa untuk mengaitkan kades verifikator di desa tersebut
        $desa = Desa::findOrFail($validated['kode_pos']);

        if (! $desa->nipd) {
            if ($isJson) {
                return response()->json(['success' => false, 'message' => 'Desa yang dipilih belum memiliki Kepala Desa aktif untuk memverifikasi.'], 422);
            }

            return back()->withErrors(['kode_pos' => 'Desa yang dipilih belum memiliki Kepala Desa aktif untuk memverifikasi.'])->withInput();
        }

        // Handle file uploads before creating the Wisata record
        $gambarPath = is_string($request->input('gambar')) ? $request->input('gambar') : '';
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('public/wisata/gambar');
        }

        $videoPath = is_string($request->input('video')) ? $request->input('video') : '';
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('public/wisata/video');
        }

        $videoPromosiPath = $validated['video_promosi'] ?? null;
        if ($pilihanPromosi === 'manual') {
            if ($request->hasFile('video_manual')) {
                $videoPromosiPath = $request->file('video_manual')->store('public/wisata/promosi');
            } elseif (! empty($validated['video_promosi_manual'])) {
                $videoPromosiPath = $validated['video_promosi_manual'];
            }
        } elseif ($pilihanPromosi === 'ai') {
            // Biarkan null saat video sedang digenerate AI di background, kecuali secara eksplisit diisi
            $videoPromosiPath = $validated['video_promosi'] ?? null;
        }

        $wisata = DB::transaction(function () use ($validated, $desa, $agensiId, $pilihanPromosi, $gambarPath, $videoPath, $videoPromosiPath) {
            $wisata = Wisata::create([
                'nama_wisata' => $validated['nama_wisata'],
                'jalan' => $validated['jalan'],
                'video_promosi' => $videoPromosiPath,
                'gambar' => $gambarPath,
                'video' => $videoPath,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status' => null, // Default: Pending verifikasi
                'kode_pos' => $desa->kode_pos,
                'agensi_id' => $agensiId,
                'nipd' => $desa->nipd, // Kepala desa di desa tersebut
            ]);

            // Jika mode AI dipilih atau ada keterangan prompt kustom, buat record draft_prompt & dispatch Job
            if ($pilihanPromosi === 'ai' || ! empty($validated['prompt_keterangan'])) {
                $customNotes = $validated['prompt_keterangan'] ?? 'Visual sinematik alam estetik, drone shot 4K, atmosfer mempesona.';

                $draft = DraftPrompt::create([
                    'timestamp' => now(),
                    'prompt_message' => $customNotes,
                    'wisata_id' => $wisata->id,
                    'agensi_id' => $agensiId,
                ]);

                GeneratePromotionalVideo::dispatch($wisata, $draft);
            }

            return $wisata;
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Objek wisata berhasil diajukan dan menunggu verifikasi Kepala Desa.',
                'data' => $wisata->load(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']),
            ], 201);
        }

        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil diajukan dan menunggu verifikasi Kepala Desa.');
    }

    /**
     * Detail objek wisata.
     */
    public function show(Wisata $wisata, Request $request): View|JsonResponse
    {
        $wisata->load(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']);

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'data' => $wisata,
            ]);
        }

        return view('wisata.show', [
            'wisata' => $wisata,
            'jsonData' => $wisata->toArray(),
        ]);
    }

    /**
     * Form edit objek wisata.
     */
    public function edit(Wisata $wisata, Request $request): View
    {
        $user = $request->user();

        // Hanya pemilik agensi atau admin yang boleh mengedit
        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit wisata ini.');
        }

        $wisata->load('draftPrompt');
        $desaList = Desa::whereNotNull('nipd')->with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();

        return view('wisata.edit', compact('wisata', 'desaList'));
    }

    /**
     * Perbarui data objek wisata.
     */
    public function update(Request $request, Wisata $wisata): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit wisata ini.');
        }

        $modePromosi = $request->input('mode_promosi');

        if ($modePromosi === 'manual' && $request->filled('video_promosi_manual') && ! $request->filled('video_promosi')) {
            $request->merge(['video_promosi' => $request->input('video_promosi_manual')]);
        }

        if ($modePromosi === 'ai' && ! $request->filled('video_promosi')) {
            $slug = Str::slug($request->input('nama_wisata', $wisata->nama_wisata));
            $request->merge(['video_promosi' => "https://fal.media/files/draft-ai/{$slug}-promosi.mp4"]);
        }

        $validated = $request->validate([
            'nama_wisata' => ['required', 'string', 'max:255'],
            'jalan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'integer', 'exists:desa,kode_pos'],
            'video_promosi' => ['required', 'string', 'max:255'],
            'gambar' => ['required', 'string', 'max:255'],
            'video' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'mode_promosi' => ['nullable', 'in:ai,manual'],
            'prompt_keterangan' => ['nullable', 'string'],
        ]);

        $desa = Desa::findOrFail($validated['kode_pos']);

        DB::transaction(function () use ($wisata, $validated, $desa, $modePromosi) {
            $wisata->update([
                'nama_wisata' => $validated['nama_wisata'],
                'jalan' => $validated['jalan'],
                'video_promosi' => $validated['video_promosi'],
                'gambar' => $validated['gambar'],
                'video' => $validated['video'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'kode_pos' => $desa->kode_pos,
                'nipd' => $desa->nipd,
            ]);

            if ($modePromosi === 'ai' || ! empty($validated['prompt_keterangan'])) {
                $customNotes = $validated['prompt_keterangan'] ?? 'Visual sinematik alam estetik, drone shot 4K, atmosfer mempesona.';
                $fullPrompt = str_starts_with($customNotes, 'Sinematik 4K')
                    ? $customNotes
                    : "Sinematik 4K drone footage video promosi untuk {$wisata->nama_wisata}, di Desa {$desa->nama_desa}, Kec. {$desa->kecamatan}. Keterangan & gaya visual: {$customNotes}";

                DraftPrompt::updateOrCreate(
                    ['wisata_id' => $wisata->id],
                    [
                        'timestamp' => now(),
                        'prompt_message' => $fullPrompt,
                        'agensi_id' => $wisata->agensi_id,
                    ]
                );
            } elseif ($modePromosi === 'manual') {
                // Jika beralih ke manual penuh, hapus draft prompt jika ada
                $wisata->draftPrompt()?->delete();
            }
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Objek wisata berhasil diperbarui.',
                'data' => $wisata->fresh(['desa', 'agensi', 'kepalaDesa', 'draftPrompt']),
            ]);
        }

        return redirect()->route('wisata.index')->with('success', 'Data wisata berhasil diperbarui.');
    }

    /**
     * Hapus objek wisata.
     */
    public function destroy(Wisata $wisata, Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $wisata->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus wisata ini.');
        }

        $wisata->delete();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Objek wisata berhasil dihapus.',
            ]);
        }

        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil dihapus.');
    }

    /**
     * Verifikasi Objek Wisata oleh Kepala Desa.
     * Hanya Kepala Desa yang wilayahnya mencakup objek wisata ini yang berhak memverifikasi.
     */
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

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => "Wisata berhasil {$statusText}.",
                'data' => $wisata->fresh(),
            ]);
        }

        return back()->with('success', "Objek wisata '{$wisata->nama_wisata}' berhasil {$statusText}.");
    }
}

