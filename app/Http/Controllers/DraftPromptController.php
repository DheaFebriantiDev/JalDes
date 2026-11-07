<?php

namespace App\Http\Controllers;

use App\Models\DraftPrompt;
use App\Models\Wisata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DraftPromptController extends Controller
{
    /**
     * Menampilkan daftar draft prompt promosi (milik agensi).
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();
        $query = DraftPrompt::query()->with(['wisata', 'agensi']);

        if ($user && $user->isAgensi() && $user->agensi) {
            $query->where('agensi_id', $user->agensi->id);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('prompt_message', 'like', "%{$search}%")
                    ->orWhereHas('wisata', function ($wq) use ($search) {
                        $wq->where('nama_wisata', 'like', "%{$search}%");
                    });
            });
        }

        $drafts = $query->latest('timestamp')->get();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'total' => $drafts->count(),
                'data' => $drafts,
            ]);
        }

        return view('draft-prompt.index', [
            'drafts' => $drafts,
            'search' => $search,
            'jsonData' => $drafts->toArray(),
        ]);
    }

    /**
     * Form buat draft prompt baru.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan membuat draft prompt.');
        }

        // Ambil wisata milik agensi yang belum memiliki draft prompt
        $wisataList = Wisata::query()
            ->when($user->isAgensi() && $user->agensi, fn ($q) => $q->where('agensi_id', $user->agensi->id))
            ->doesntHave('draftPrompt')
            ->orderBy('nama_wisata', 'asc')
            ->get();

        return view('draft-prompt.create', compact('wisataList'));
    }

    /**
     * Simpan draft prompt baru.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user->isAgensi() && ! $user->isAdmin()) {
            abort(403, 'Hanya akun agensi yang diizinkan membuat draft prompt.');
        }

        $agensiId = $user->isAgensi() ? $user->agensi?->id : $request->input('agensi_id');

        $validated = $request->validate([
            'prompt_message' => ['required', 'string'],
            'wisata_id' => ['required', 'integer', 'exists:wisata,id', 'unique:draft_prompt,wisata_id'],
        ]);

        $draft = DraftPrompt::create([
            'timestamp' => now(),
            'prompt_message' => $validated['prompt_message'],
            'wisata_id' => $validated['wisata_id'],
            'agensi_id' => $agensiId,
        ]);

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Draft prompt berhasil disimpan.',
                'data' => $draft->load('wisata'),
            ], 201);
        }

        return redirect()->route('draft-prompt.index')->with('success', 'Draft prompt promosi berhasil disimpan.');
    }

    /**
     * Detail draft prompt.
     */
    public function show(DraftPrompt $draftPrompt, Request $request): View|JsonResponse
    {
        $draftPrompt->load(['wisata', 'agensi']);

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'data' => $draftPrompt,
            ]);
        }

        return view('draft-prompt.show', [
            'draft' => $draftPrompt,
            'jsonData' => $draftPrompt->toArray(),
        ]);
    }

    /**
     * Hapus draft prompt.
     */
    public function destroy(DraftPrompt $draftPrompt, Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && (! $user->isAgensi() || $user->agensi?->id !== $draftPrompt->agensi_id)) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus draft prompt ini.');
        }

        $draftPrompt->delete();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Draft prompt berhasil dihapus.',
            ]);
        }

        return redirect()->route('draft-prompt.index')->with('success', 'Draft prompt berhasil dihapus.');
    }
}

