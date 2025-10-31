<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    // Tampilkan semua data QR Code
    public function index()
    {
        $data = KisQrCode::with('pengunjung')->get();
        return response()->json($data);
    }

    // Tambah QR Code baru
    public function store(Request $request)
    {
        $request->validate([
            'pengunjung_id' => 'required',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'required|date|after:berlaku_mulai'
        ]);

        $kodeQR = Str::uuid(); // generate unik
        $qr = KisQrCode::create([
            'pengunjung_id' => $request->pengunjung_id,
            'qr_code' => $kodeQR,
            'berlaku_mulai' => $request->berlaku_mulai,
            'berlaku_sampai' => $request->berlaku_sampai,
            'created_by' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dibuat',
            'data' => $qr
        ]);
    }

    // Lihat detail 1 QR Code
    public function show($id)
    {
        $qr = KisQrCode::with('pengunjung')->findOrFail($id);
        return response()->json($qr);
    }

    // Update QR Code
    public function update(Request $request, $id)
    {
        $qr = KisQrCode::findOrFail($id);
        $qr->update([
            'berlaku_mulai' => $request->berlaku_mulai ?? $qr->berlaku_mulai,
            'berlaku_sampai' => $request->berlaku_sampai ?? $qr->berlaku_sampai,
            'edited_by' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil diperbarui',
            'data' => $qr
        ]);
    }

    // Hapus QR Code
    public function destroy($id)
    {
        $qr = KisQrCode::findOrFail($id);
        $qr->delete();

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dihapus'
        ]);
    }
}
