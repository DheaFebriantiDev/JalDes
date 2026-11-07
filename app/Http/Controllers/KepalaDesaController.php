<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\KepalaDesa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KepalaDesaController extends Controller
{
    /**
     * Menampilkan daftar kepala desa.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = KepalaDesa::query()->with('desa');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nipd', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhereHas('desa', function ($dq) use ($search) {
                        $dq->where('nama_desa', 'like', "%{$search}%")
                            ->orWhere('kecamatan', 'like', "%{$search}%");
                    });
            });
        }

        $kadesList = $query->orderBy('status', 'desc')->orderBy('nama', 'asc')->get();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'total' => $kadesList->count(),
                'data' => $kadesList,
            ]);
        }

        return view('kepala-desa.index', [
            'kadesList' => $kadesList,
            'search' => $search,
            'jsonData' => $kadesList->toArray(),
        ]);
    }

    /**
     * Form pembuatan kepala desa.
     * Alur:
     * 1. Cukup masukkan kode pos desa.
     * 2. Jika desa sudah ada, kades tinggal mengisi data diri.
     * 3. Jika kode pos belum ada, ada tombol untuk menampilkan form wilayah desa baru.
     */
    public function create(): View
    {
        $desaList = Desa::with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();

        return view('kepala-desa.create', compact('desaList'));
    }

    /**
     * Cek informasi desa berdasarkan kode pos (AJAX helper untuk frontend/view).
     */
    public function cekKodePos(int|string $kodePos): JsonResponse
    {
        $desa = Desa::where('kode_pos', $kodePos)->with('kepalaDesa')->first();

        if (! $desa) {
            return response()->json([
                'exists' => false,
                'message' => 'Desa dengan kode pos tersebut belum terdaftar.',
            ]);
        }

        return response()->json([
            'exists' => true,
            'desa' => [
                'kode_pos' => $desa->kode_pos,
                'nama_desa' => $desa->nama_desa,
                'kecamatan' => $desa->kecamatan,
                'kota' => $desa->kota,
                'provinsi' => $desa->provinsi,
                'kades_saat_ini' => $desa->kepalaDesa ? [
                    'nipd' => $desa->kepalaDesa->nipd,
                    'nama' => $desa->kepalaDesa->nama,
                    'status' => $desa->kepalaDesa->status,
                ] : null,
            ],
        ]);
    }

    /**
     * Simpan data kepala desa baru.
     * Menerapkan algoritma pergantian: jika desa sebelumnya sudah memiliki kades,
     * kades lama otomatis dinonaktifkan (status = false), kades baru aktif (status = true).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $desaExists = Desa::where('kode_pos', $request->input('kode_pos'))->exists();

        $rules = [
            'kode_pos' => ['required', 'integer'],
            // Data Diri Kades
            'nipd' => ['required', 'string', 'max:50', 'unique:kepala_desa,nipd'],
            'nik' => ['required', 'string', 'max:50', 'unique:kepala_desa,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:50'],
            // Kredensial Akun Login (Opsional)
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
            // Form wilayah desa baru (hanya wajib jika desa belum ada di DB)
            'nama_desa' => [$desaExists ? 'nullable' : 'required', 'string', 'max:255'],
            'kecamatan' => [$desaExists ? 'nullable' : 'required', 'string', 'max:255'],
            'kota' => [$desaExists ? 'nullable' : 'required', 'string', 'max:255'],
            'provinsi' => [$desaExists ? 'nullable' : 'required', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        $kades = DB::transaction(function () use ($validated) {
            $desa = Desa::where('kode_pos', $validated['kode_pos'])->first();

            // Jika desa belum ada, buat desa baru berdasarkan form tambahan
            if (! $desa) {
                $desa = Desa::create([
                    'kode_pos' => $validated['kode_pos'],
                    'nama_desa' => $validated['nama_desa'],
                    'kecamatan' => $validated['kecamatan'],
                    'kota' => $validated['kota'],
                    'provinsi' => $validated['provinsi'],
                    'nipd' => null,
                ]);
            }

            // ALGORITMA PERGANTIAN KEPALA DESA:
            // Jika desa sebelumnya sudah memiliki kepala desa menjabat, non-aktifkan kades lama
            if ($desa->nipd) {
                KepalaDesa::where('nipd', $desa->nipd)->update(['status' => false]);
            }

            // Buat akun login User untuk Kades baru
            $username = ! empty($validated['username']) ? $validated['username'] : 'kades_'.$validated['nipd'];
            $email = ! empty($validated['email']) ? $validated['email'] : 'kades_'.$validated['nipd'].'@jaldes.id';
            $password = Hash::make(! empty($validated['password']) ? $validated['password'] : 'password');

            $user = User::create([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'kd',
            ]);

            // Buat data Kepala Desa baru dengan status aktif (true)
            $newKades = KepalaDesa::create([
                'nipd' => $validated['nipd'],
                'nik' => $validated['nik'],
                'nama' => $validated['nama'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'status' => true,
                'user_id' => $user->id,
            ]);

            // Hubungkan kades baru ke desa tersebut
            $desa->update(['nipd' => $newKades->nipd]);

            return $newKades;
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Kepala Desa berhasil didaftarkan dan diaktifkan.',
                'data' => $kades->load('desa'),
            ], 201);
        }

        return redirect()->route('desa.index')->with('success', "Kepala Desa '{$kades->nama}' berhasil ditugaskan dan diaktifkan.");
    }
}

