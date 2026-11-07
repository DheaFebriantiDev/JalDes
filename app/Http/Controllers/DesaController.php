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

class DesaController extends Controller
{
    /**
     * Menampilkan daftar desa dan kepala desanya.
     * Urutan: nama desa (A-Z), lalu nama kades (A-Z).
     * Fitur search: nama desa, kecamatan, kota, provinsi, nama kades, NIPD.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');

        $query = Desa::query()->with('kepalaDesa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_desa', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('provinsi', 'like', "%{$search}%")
                    ->orWhereHas('kepalaDesa', function ($kq) use ($search) {
                        $kq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nipd', 'like', "%{$search}%");
                    });
            });
        }

        // Urutan: pertama nama desa berdasarkan huruf (A-Z), lalu kadesnya (A-Z)
        $desaList = $query
            ->leftJoin('kepala_desa', 'desa.nipd', '=', 'kepala_desa.nipd')
            ->select('desa.*')
            ->orderBy('desa.nama_desa', 'asc')
            ->orderBy('kepala_desa.nama', 'asc')
            ->get();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'total' => $desaList->count(),
                'data' => $desaList,
            ]);
        }

        return view('desa.index', [
            'desaList' => $desaList,
            'search' => $search,
            'jsonData' => $desaList->toArray(),
        ]);
    }

    /**
     * Form tambah desa / penugasan kades.
     */
    public function create(): View
    {
        $availableKades = KepalaDesa::doesntHave('desa')->get();
        $desaList = Desa::with('kepalaDesa')->orderBy('nama_desa', 'asc')->get();

        return view('desa.create', compact('availableKades', 'desaList'));
    }

    /**
     * Simpan data desa / penugasan kepala desa.
     * Logika Alur Sistem:
     * - Jika kode_pos SUDAH ADA di database:
     *   Tidak perlu menambah data desa baru (pakai desa yang ada).
     *   Jika kades baru diisi atau kades dipilih, kades lama di desa tersebut
     *   otomatis dinonaktifkan (status = false), kades baru diaktifkan (status = true).
     * - Jika kode_pos BELUM ADA di database:
     *   Membuat data desa baru beserta penugasan kadesnya.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $desaExists = Desa::where('kode_pos', $request->input('kode_pos'))->exists();

        $rules = [
            'kode_pos' => ['required', 'integer'],
            'nipd' => ['nullable', 'string', 'exists:kepala_desa,nipd'],
            // Input data kades baru
            'kades_nipd' => ['nullable', 'string', 'unique:kepala_desa,nipd'],
            'kades_nik' => ['nullable', 'string', 'unique:kepala_desa,nik'],
            'kades_nama' => ['nullable', 'string', 'max:255'],
            'kades_telepon' => ['nullable', 'string', 'max:50'],
            'kades_username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'kades_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'kades_password' => ['nullable', 'string', 'min:6'],
        ];

        // Validasi isian wilayah desa hanya diwajibkan bila desa belum ada di database
        if (! $desaExists) {
            $rules['nama_desa'] = ['required', 'string', 'max:255'];
            $rules['provinsi'] = ['required', 'string', 'max:255'];
            $rules['kota'] = ['required', 'string', 'max:255'];
            $rules['kecamatan'] = ['required', 'string', 'max:255'];
        } else {
            $rules['nama_desa'] = ['nullable', 'string', 'max:255'];
            $rules['provinsi'] = ['nullable', 'string', 'max:255'];
            $rules['kota'] = ['nullable', 'string', 'max:255'];
            $rules['kecamatan'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $desa = DB::transaction(function () use ($validated, $request, $desaExists) {
            $desa = Desa::where('kode_pos', $validated['kode_pos'])->first();

            // 1. Jika desa belum ada, buat data desa baru
            if (! $desa) {
                $desa = Desa::create([
                    'kode_pos' => $validated['kode_pos'],
                    'nama_desa' => $validated['nama_desa'],
                    'provinsi' => $validated['provinsi'],
                    'kota' => $validated['kota'],
                    'kecamatan' => $validated['kecamatan'],
                    'nipd' => null,
                ]);
            }

            $nipd = $validated['nipd'] ?? null;

            // 2. Jika ada input data kepala desa baru
            if (! empty($validated['kades_nipd']) && ! empty($validated['kades_nik']) && ! empty($validated['kades_nama'])) {
                // ALGORITMA PERGANTIAN: Nonaktifkan kades lama jika sebelumnya ada kades di desa ini
                if ($desa->nipd) {
                    KepalaDesa::where('nipd', $desa->nipd)->update(['status' => false]);
                }

                $username = ! empty($validated['kades_username']) ? $validated['kades_username'] : 'kades_'.$validated['kades_nipd'];
                $email = ! empty($validated['kades_email']) ? $validated['kades_email'] : 'kades_'.$validated['kades_nipd'].'@jaldes.id';
                $password = Hash::make(! empty($validated['kades_password']) ? $validated['kades_password'] : 'password');

                $user = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'kd',
                ]);

                $kades = KepalaDesa::create([
                    'nipd' => $validated['kades_nipd'],
                    'nik' => $validated['kades_nik'],
                    'nama' => $validated['kades_nama'],
                    'nomor_telepon' => ! empty($validated['kades_telepon']) ? $validated['kades_telepon'] : '-',
                    'status' => true,
                    'user_id' => $user->id,
                ]);

                $nipd = $kades->nipd;
            } elseif ($nipd && $nipd !== $desa->nipd) {
                // Jika memilih kades yang sudah ada dan berbeda dari kades saat ini
                if ($desa->nipd) {
                    KepalaDesa::where('nipd', $desa->nipd)->update(['status' => false]);
                }
                KepalaDesa::where('nipd', $nipd)->update(['status' => true]);
            }

            // 3. Update relasi desa dengan kades baru
            if ($nipd) {
                $desa->update(['nipd' => $nipd]);
            }

            return $desa;
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Data desa & kepala desa berhasil disimpan.',
                'data' => $desa->load('kepalaDesa'),
            ], 201);
        }

        return redirect()->route('desa.index')->with('success', 'Data berhasil disimpan dan kepala desa berhasil ditugaskan.');
    }

    /**
     * Tampilkan detail desa.
     */
    public function show(Desa $desa, Request $request): View|JsonResponse
    {
        $desa->load('kepalaDesa');

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'data' => $desa,
            ]);
        }

        return view('desa.show', [
            'desa' => $desa,
            'jsonData' => $desa->toArray(),
        ]);
    }

    /**
     * Form edit desa & pergantian kepala desa.
     */
    public function edit(Desa $desa): View
    {
        $desa->load('kepalaDesa');
        $allKades = KepalaDesa::orderBy('nama', 'asc')->get();

        return view('desa.edit', compact('desa', 'allKades'));
    }

    /**
     * Update data desa dan terapkan algoritma pergantian kepala desa:
     * Jika sebelumnya ada kades, kades lama di-nonaktifkan (status = false),
     * kemudian digantikan dengan kades baru yang aktif (status = true).
     */
    public function update(Request $request, Desa $desa): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'nama_desa' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kota' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            // Opsi 1: Pilih dari kades yang ada
            'nipd' => ['nullable', 'string', 'exists:kepala_desa,nipd'],
            // Opsi 2: Buat kades baru langsung
            'new_kades_nipd' => ['nullable', 'string', 'unique:kepala_desa,nipd'],
            'new_kades_nik' => ['nullable', 'string', 'unique:kepala_desa,nik'],
            'new_kades_nama' => ['nullable', 'string', 'max:255'],
            'new_kades_telepon' => ['nullable', 'string', 'max:50'],
            'new_kades_username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'new_kades_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'new_kades_password' => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($validated, $desa) {
            $targetNipd = $desa->nipd;

            // Jika input kades baru dibuat
            if (! empty($validated['new_kades_nipd']) && ! empty($validated['new_kades_nik']) && ! empty($validated['new_kades_nama'])) {
                $username = ! empty($validated['new_kades_username']) ? $validated['new_kades_username'] : 'kades_'.$validated['new_kades_nipd'];
                $email = ! empty($validated['new_kades_email']) ? $validated['new_kades_email'] : 'kades_'.$validated['new_kades_nipd'].'@jaldes.id';
                $password = Hash::make(! empty($validated['new_kades_password']) ? $validated['new_kades_password'] : 'password');

                $user = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'kd',
                ]);

                $newKades = KepalaDesa::create([
                    'nipd' => $validated['new_kades_nipd'],
                    'nik' => $validated['new_kades_nik'],
                    'nama' => $validated['new_kades_nama'],
                    'nomor_telepon' => ! empty($validated['new_kades_telepon']) ? $validated['new_kades_telepon'] : '-',
                    'status' => true,
                    'user_id' => $user->id,
                ]);

                $targetNipd = $newKades->nipd;
            } elseif (array_key_exists('nipd', $validated)) {
                $targetNipd = $validated['nipd'];
            }

            // ALGORITMA PERGANTIAN KEPALA DESA:
            // Jika ada pergantian (kades baru berbeda dengan kades lama)
            if ($targetNipd !== $desa->nipd) {
                // 1. Non-aktifkan data kepala desa lama jika sebelumnya ada
                if ($desa->nipd) {
                    KepalaDesa::where('nipd', $desa->nipd)->update(['status' => false]);
                }

                // 2. Aktifkan kepala desa yang baru jika ada
                if ($targetNipd) {
                    KepalaDesa::where('nipd', $targetNipd)->update(['status' => true]);
                }
            }

            $desa->update([
                'nama_desa' => $validated['nama_desa'],
                'provinsi' => $validated['provinsi'],
                'kota' => $validated['kota'],
                'kecamatan' => $validated['kecamatan'],
                'nipd' => $targetNipd,
            ]);
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Desa & Kepala Desa berhasil diperbarui.',
                'data' => $desa->fresh('kepalaDesa'),
            ]);
        }

        return redirect()->route('desa.index')->with('success', 'Data Desa dan Kepala Desa berhasil diperbarui.');
    }

    /**
     * Hapus desa.
     * Jika desa memiliki kepala desa, kades tersebut di-nonaktifkan statusnya.
     */
    public function destroy(Desa $desa, Request $request): RedirectResponse|JsonResponse
    {
        DB::transaction(function () use ($desa) {
            if ($desa->nipd) {
                KepalaDesa::where('nipd', $desa->nipd)->update(['status' => false]);
            }
            $desa->delete();
        });

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'message' => 'Desa berhasil dihapus.',
            ]);
        }

        return redirect()->route('desa.index')->with('success', 'Desa berhasil dihapus.');
    }
}
