<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GudangModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $gudang = GudangModel::all();

            return response()->json([
                'status' => true,
                'message' => 'Data gudang berhasil diambil',
                'data' => $gudang
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gudang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_gudang' => 'required|string|max:255',
            'lokasi_gudang' => 'required|string|max:255',
            'status_gudang' => 'required|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gudang = GudangModel::create([
                'nama_gudang' => $request->nama_gudang,
                'lokasi_gudang' => $request->lokasi_gudang,
                'status_gudang' => $request->status_gudang
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Gudang berhasil dibuat',
                'data' => $gudang
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat membuat gudang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $gudang = GudangModel::find($id);

            if (!$gudang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gudang tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data gudang berhasil diambil',
                'data' => $gudang
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gudang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_gudang' => 'sometimes|string|max:255',
            'lokasi_gudang' => 'sometimes|string|max:255',
            'status_gudang' => 'sometimes|integer|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gudang = GudangModel::find($id);

            if (!$gudang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gudang tidak ditemukan'
                ], 404);
            }

            $gudang->update($request->only(['nama_gudang', 'lokasi_gudang', 'status_gudang']));

            return response()->json([
                'status' => true,
                'message' => 'Gudang berhasil diperbarui',
                'data' => $gudang
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui gudang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $gudang = GudangModel::find($id);

            if (!$gudang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gudang tidak ditemukan'
                ], 404);
            }

            $gudang->delete();

            return response()->json([
                'status' => true,
                'message' => 'Gudang berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus gudang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gudang with related ruangan data
     */
    public function getWithRuangan(string $id): JsonResponse
    {
        try {
            $gudang = GudangModel::with('getDataRuangan')->find($id);

            if (!$gudang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gudang tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data gudang dengan ruangan berhasil diambil',
                'data' => $gudang
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gudang dengan ruangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only active gudang (status_gudang = 1)
     */
    public function getActiveGudang(): JsonResponse
    {
        try {
            $gudang = GudangModel::where('status_gudang', 1)->get();

            return response()->json([
                'status' => true,
                'message' => 'Data gudang aktif berhasil diambil',
                'data' => $gudang
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gudang aktif',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}