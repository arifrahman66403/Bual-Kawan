<?php

namespace App\Http\Controllers;

use App\Models\KisQrCode;
use Illuminate\Http\Request;
use App\Models\KisPengunjung;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KisQrCodeController extends Controller
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
        $request->validate([
            'pengunjung_id' => 'required|exists:kis_pengunjung,id',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'required|date|after:berlaku_mulai',
        ]);

        // 1️⃣ Generate kode unik
        $kodeQr = strtoupper(Str::random(10));

        // 2️⃣ Tentukan nama file QR
        $fileName = 'qr_' . $kodeQr . '.png';
        $filePath = public_path('qr/' . $fileName);

        // 3️⃣ Generate QR code image
        QrCode::format('png')->size(250)->generate($kodeQr, $filePath);

        // 4️⃣ Simpan ke database
        $qr = KisQrCode::create([
            'pengunjung_id' => $request->pengunjung_id,
            'kode_qr' => $kodeQr,
            'path_qr' => 'qr/' . $fileName,
            'berlaku_mulai' => $request->berlaku_mulai,
            'berlaku_sampai' => $request->berlaku_sampai,
            'status' => 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dibuat',
            'data' => $qr,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KisQrCode $kisQrCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KisQrCode $kisQrCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KisQrCode $kisQrCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KisQrCode $kisQrCode)
    {
        //
    }
}
