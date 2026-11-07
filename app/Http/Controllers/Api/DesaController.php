<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesaController extends Controller
{
    // GET /api/desa
    public function index()
    {
        $desa = Desa::with('kepalaDesa')->get();
        return response()->json(['success' => true, 'data' => $desa]);
    }

    // POST /api/desa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_pos' => 'required|unique:desa,kode_pos',
            'nama_desa' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'nipd' => 'nullable|exists:kepala_desa,nipd',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $desa = Desa::create($request->all());
        return response()->json(['success' => true, 'message' => 'Desa berhasil ditambahkan', 'data' => $desa]);
    }

    // PUT /api/desa/{kode_pos}
    public function update(Request $request, $kode_pos)
    {
        $desa = Desa::find($kode_pos);
        if (!$desa) return response()->json(['success' => false, 'message' => 'Desa tidak ditemukan'], 404);

        $desa->update($request->all());
        return response()->json(['success' => true, 'message' => 'Desa berhasil diupdate']);
    }

    // DELETE /api/desa/{kode_pos}
    public function destroy($kode_pos)
    {
        $desa = Desa::find($kode_pos);
        if (!$desa) return response()->json(['success' => false, 'message' => 'Desa tidak ditemukan'], 404);
        
        $desa->delete();
        return response()->json(['success' => true, 'message' => 'Desa berhasil dihapus']);
    }
}