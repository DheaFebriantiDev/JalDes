<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KepalaDesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KadesController extends Controller
{
    // 1. GET /api/kades
    public function index()
    {
        $kades = KepalaDesa::with('user')->get();
        return response()->json([
            'success' => true,
            'data' => $kades
        ]);
    }

    // 2. POST /api/kades
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nipd' => 'required|unique:kepala_desa,nipd',
            'nik' => 'required',
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'kd',
            ]);

            $kades = KepalaDesa::create([
                'nipd' => $request->nipd,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'status' => true,
                'user_id' => $user->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kades berhasil ditambahkan', 'data' => $kades]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // 3. PUT /api/kades/{nipd}
    public function update(Request $request, $nipd)
    {
        $kades = KepalaDesa::find($nipd);
        if (!$kades) return response()->json(['success' => false, 'message' => 'Kades tidak ditemukan'], 404);

        $user = $kades->user;

        DB::beginTransaction();
        try {
            $kades->update([
                'nama' => $request->nama ?? $kades->nama,
                'nik' => $request->nik ?? $kades->nik,
                'nomor_telepon' => $request->nomor_telepon ?? $kades->nomor_telepon,
            ]);

            if ($user) {
                $user->update([
                    'username' => $request->username ?? $user->username,
                    'email' => $request->email ?? $user->email,
                ]);
                if ($request->filled('password')) {
                    $user->update(['password' => Hash::make($request->password)]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kades berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // 4. DELETE /api/kades/{nipd}
    public function destroy($nipd)
    {
        $kades = KepalaDesa::find($nipd);
        if (!$kades) return response()->json(['success' => false, 'message' => 'Kades tidak ditemukan'], 404);

        $user = $kades->user;

        DB::beginTransaction();
        try {
            $kades->delete();
            if ($user) $user->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kades berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}