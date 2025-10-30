<?php

namespace App\Http\Controllers;

use App\Models\KisTracking;
use Illuminate\Http\Request;
use App\Models\KisQrCode;
use Carbon\Carbon;

class KisTrackingController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KisTracking $kisTracking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KisTracking $kisTracking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KisTracking $kisTracking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KisTracking $kisTracking)
    {
        //
    }

    public function scan(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string'
        ]);

        // Cek QR valid
        $qr = KisQrCode::where('kode_qr', $request->kode_qr)->first();
        if (!$qr) {
            return response()->json(['message' => 'QR tidak ditemukan'], 404);
        }

        // Cek tracking pengunjung
        $tracking = KisTracking::firstOrNew(['pengunjung_id' => $qr->pengunjung_id]);

        if ($tracking->status == 'disetujui' || !$tracking->exists) {
            $tracking->status = 'kunjungan';
            $tracking->waktu_masuk = Carbon::now();
        } elseif ($tracking->status == 'kunjungan') {
            $tracking->status = 'selesai';
            $tracking->waktu_keluar = Carbon::now();
        }

        $tracking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status kunjungan diperbarui menjadi ' . $tracking->status,
            'data' => $tracking
        ]);
    }
}
