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
     * Menyimpan data agensi baru ke database.
     * Algoritma:
     * - Create agensi dianggap sebagai registrasi agensi.
     * - Setiap user yang dibuat otomatis memiliki role 'agensi'.
     * - Menerima sekurang-kurangnya 1 user (bisa lebih banyak dengan fitur tambah user dinamis).
     * - User pertama menjadi akun utama yang dihubungkan ke agensi (user_id).
     * - Jika pendaftar adalah guest, sistem langsung mengautentikasi (login) user utama.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validasi input agensi dan array users
        $validated = $request->validate([
            'nomor_telepon' => ['required', 'string', 'max:255'],
            'nama_agensi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'npwp' => ['nullable', 'string', 'unique:agensi,npwp'],
            // Pilihan menghubungkan user yang sudah ada
            'existing_user_id' => ['nullable', 'exists:users,id', 'unique:agensi,user_id'],
            // Pilihan input user baru (minimal 1 user)
            'users' => ['nullable', 'array', 'min:1'],
            'users.*.username' => ['required_with:users', 'string', 'max:255', 'unique:users,username'],
            'users.*.email' => ['required_with:users', 'email', 'max:255', 'unique:users,email'],
            'users.*.password' => ['required_with:users', 'string', 'min:6'],
        ]);

        $agensi = DB::transaction(function () use ($validated, $request) {
            $primaryUserId = $validated['existing_user_id'] ?? null;
            $createdUsers = [];

            // Jika ada isian pembuatan user baru
            if (! empty($validated['users'])) {
                foreach ($validated['users'] as $u) {
                    $newUser = User::create([
                        'username' => $u['username'],
                        'email' => $u['email'],
                        'password' => Hash::make($u['password']),
                        'role' => 'agensi', // Auto role agensi
                    ]);

                    $createdUsers[] = $newUser;
                }

                // User pertama menjadi user_id utama agensi
                if (! $primaryUserId && count($createdUsers) > 0) {
                    $primaryUserId = $createdUsers[0]->id;
                }
            }

            // Jika user_id masih belum ada, gunakan user yang sedang login (jika ada)
            if (! $primaryUserId && Auth::check()) {
                $primaryUserId = Auth::id();
            }

            // Simpan data agensi
            $agensi = Agensi::create([
                'nomor_telepon' => $validated['nomor_telepon'],
                'nama_agensi' => $validated['nama_agensi'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'npwp' => $validated['npwp'] ?? null,
                'user_id' => $primaryUserId,
            ]);

            // Jika pendaftaran dilakukan oleh guest, otomatis login dengan akun user utama
            if (! Auth::check() && count($createdUsers) > 0) {
                Auth::login($createdUsers[0]);
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

        // Jika pendaftar diarahkan ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('success', "Selamat datang di JalDes! Agensi '{$agensi->nama_agensi}' berhasil didaftarkan.");
        }

        return redirect()->route('login')
            ->with('success', 'Pendaftaran agensi berhasil. Silakan masuk dengan akun yang telah didaftarkan.');
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
