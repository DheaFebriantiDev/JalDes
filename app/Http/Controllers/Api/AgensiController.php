<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AgensiController extends Controller
{
    // GET /api/agensi
    public function index()
    {
        // Ambil semua data agensi beserta user-nya
        $agensi = Agensi::with('user')->get();
        return response()->json(['success' => true, 'data' => $agensi]);
    }

    // POST /api/agensi
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_agensi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string',
            'npwp' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Bikin akun User
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'agensi',
            ]);

            // 2. Bikin profil Agensi
            $agensi = Agensi::create([
                'id' => Str::uuid()->toString(),
                'nama_agensi' => $request->nama_agensi,
                'nomor_telepon' => $request->nomor_telepon,
                'npwp' => $request->npwp,
                'deskripsi' => $request->deskripsi,
                'user_id' => $user->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Agensi berhasil ditambahkan', 'data' => $agensi]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // PUT /api/agensi/{id}
    public function update(Request $request, $id)
    {
        $agensi = Agensi::find($id);
        if (!$agensi) return response()->json(['success' => false, 'message' => 'Agensi tidak ditemukan'], 404);
        
        $user = $agensi->user;

        DB::beginTransaction();
        try {
            // Update profil
            $agensi->update([
                'nama_agensi' => $request->nama_agensi ?? $agensi->nama_agensi,
                'nomor_telepon' => $request->nomor_telepon ?? $agensi->nomor_telepon,
                'npwp' => $request->npwp ?? $agensi->npwp,
                'deskripsi' => $request->deskripsi ?? $agensi->deskripsi,
            ]);

            // Update akun user
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
            return response()->json(['success' => true, 'message' => 'Agensi berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // DELETE /api/agensi/{id}
    public function destroy($id)
    {
        $agensi = Agensi::find($id);
        if (!$agensi) return response()->json(['success' => false, 'message' => 'Agensi tidak ditemukan'], 404);

        $user = $agensi->user;

        DB::beginTransaction();
        try {
            $agensi->delete();
            if ($user) $user->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Agensi berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}