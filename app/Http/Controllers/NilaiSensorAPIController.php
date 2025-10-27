<?php

namespace App\Http\Controllers;

use App\Models\NilaiSensorModel;
use App\Models\SensorModel;
use Illuminate\Http\Request;

class NilaiSensorAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dataSensor = SensorModel::findOrFail($request->id_sensor);

            NilaiSensorModel::create([
                'id_sensor' => $request->id_sensor,
                'nilai_sensor' => $request->nilai_sensor,
                'rssi' => $request->rssi,
                'snr' => $request->snr,
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Data berhasil ditambahkan!',
                'request_data' => $request->all()
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Ada kesalahan saat menambahkan data!',
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
