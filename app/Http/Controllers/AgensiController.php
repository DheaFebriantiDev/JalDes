<?php

namespace App\Http\Controllers;

use App\Models\Agensi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AgensiController extends Controller
{
    /**
     * Menampilkan data Agensi beserta fitur Search
     */
    public function index(Request $request): View|JsonResponse
    {
        // Menangkap input pencarian dari request
        $search = $request->input('search');

        // Mengambil data agensi dengan relasi user
        $agensi = Agensi::with(['user', 'wisata', 'draftPrompts'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_agensi', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%")
                    ->orWhere('npwp', 'like', "%{$search}%");
            })
            ->latest() // Mengurutkan dari yang terbaru
            ->paginate(10) // Pagination 10 data per halaman
            ->withQueryString(); // Mempertahankan query parameter search saat pindah halaman paginasi

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'data' => $agensi,
            ]);
        }

        return view('agensi.index', [
            'agensi' => $agensi,
            'search' => $search,
            'jsonData' => $agensi->items(),
        ]);
    }

    /**
     * Menampilkan form tambah data / Register Agensi
     */
    public function create(): View
    {
        // Mengambil data user yang belum memiliki agensi (karena di tabel user_id bersifat unique)
        $users = User::where('role', 'agensi')->doesntHave('agensi')->get();

        return view('agensi.create', compact('users'));
    }

    /**
     * Menyimpan data agensi baru ke database (Registrasi Agensi).
     * Aturan relasi: 1 User hanya memiliki 1 Agensi (1-to-1).
     * Saat mendaftar agensi, dibuatkan 1 akun user dengan role otomatis 'agensi'.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validasi input agensi dan akun user (1 user = 1 agensi)
        $validated = $request->validate([
            // Data Agensi
            'nama_agensi' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'unique:agensi,npwp'],
            'deskripsi' => ['nullable', 'string'],
            // Akun User Pemilik Agensi (1 user = 1 agensi)
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $agensi = DB::transaction(function () use ($validated, $request) {
            // 1. Buat 1 akun User dengan role otomatis 'agensi'
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'agensi',
            ]);

            // 2. Buat data Agensi yang berelasi 1-to-1 dengan user tersebut
            $agensi = Agensi::create([
                'nomor_telepon' => $validated['nomor_telepon'],
                'nama_agensi' => $validated['nama_agensi'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'npwp' => $validated['npwp'] ?? null,
                'user_id' => $user->id,
            ]);

            // 3. Jika pendaftar adalah guest, langsung login dengan akun user tersebut
            if (! Auth::check()) {
                Auth::login($user);
                $request->session()->regenerate();
            }

            return $agensi;
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Agensi berhasil didaftarkan.',
                'data' => $agensi->load('user'),
            ], 201);
        }

        // Arahkan ke dashboard agensi
        return redirect()->route('dashboard')
            ->with('success', "Selamat datang di JalDes! Agensi '{$agensi->nama_agensi}' berhasil didaftarkan.");
    }

    /**
     * Menampilkan detail agensi
     */
    public function show(Agensi $agensi, Request $request): View|JsonResponse
    {
        $agensi->load(['user', 'wisata', 'draftPrompts']); // Load relasi untuk detail

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'data' => $agensi,
            ]);
        }

        return view('agensi.show', [
            'agensi' => $agensi,
            'jsonData' => $agensi->toArray(),
        ]);
    }

    /**
     * Menampilkan form edit data
     */
    public function edit(Agensi $agensi): View
    {
        // Mengambil user saat ini + user lain yang belum punya agensi
        $users = User::where('id', $agensi->user_id)->orDoesntHave('agensi')->get();

        return view('agensi.edit', compact('agensi', 'users'));
    }

    /**
     * Menyimpan perubahan data agensi
     */
    public function update(Request $request, Agensi $agensi): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'nomor_telepon' => ['required', 'string', 'max:255'],
            'nama_agensi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'npwp' => ['nullable', 'string', 'unique:agensi,npwp,'.$agensi->id],
            'user_id' => ['required', 'exists:users,id', 'unique:agensi,user_id,'.$agensi->id],
        ]);

        $agensi->update($validated);

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Data Agensi berhasil diperbarui.',
                'data' => $agensi->fresh(['user']),
            ]);
        }

        return redirect()->route('agensi.index')
            ->with('success', 'Data Agensi berhasil diperbarui.');
    }

    /**
     * Menghapus data agensi
     */
    public function destroy(Agensi $agensi, Request $request): RedirectResponse|JsonResponse
    {
        $agensi->delete();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Data Agensi berhasil dihapus.',
            ]);
        }

        return redirect()->route('agensi.index')
            ->with('success', 'Data Agensi berhasil dihapus.');
    }
}
