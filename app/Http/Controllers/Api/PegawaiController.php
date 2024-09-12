<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Validator;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar pegawai.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $pegawais = Pegawai::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Pegawai',
            'data' => $pegawais
        ], 200);
    }

    /**
     * Menambahkan pegawai baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawais,nip',
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Buat pegawai baru
        $pegawai = Pegawai::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $pegawai
        ], 201);
    }

    /**
     * Menampilkan detail pegawai.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Pegawai',
            'data' => $pegawai
        ], 200);
    }

    /**
     * Memperbarui pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawais,nip,' . $pegawai->id,
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Update data pegawai
        $pegawai->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil diperbarui',
            'data' => $pegawai
        ], 200);
    }

    /**
     * Menghapus pegawai.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        $pegawai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil dihapus',
        ], 200);
    }
}