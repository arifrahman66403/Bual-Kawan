<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KisQrCodeController extends Controller
{
    public function index()
    {
        $data = KisQrCode::with('pengunjung')->latest()->get();
            return view('qr.index', compact('qrCodes'));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengunjung_id' => 'required|exists:kis_pengunjung,id',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'required|date|after:berlaku_mulai',
        ]);

        // Pastikan folder QR ada
        if (!file_exists(public_path('qr'))) {
            mkdir(public_path('qr'), 0777, true);
        }

        // Generate kode unik
        $kodeQr = strtoupper(Str::random(10));

        // Tentukan nama file QR
        $fileName = 'qr_' . $kodeQr . '.png';
        $filePath = public_path('qr/' . $fileName);

        // Generate QR code image
        QrCode::format('png')->size(250)->generate($kodeQr, $filePath);

        // Simpan ke database
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

    public function update(Request $request, KisQrCode $kisQrCode)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kisQrCode->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status QR Code diperbarui',
            'data' => $kisQrCode
        ]);
    }

    public function destroy($id)
    {
        $qr = KisQrCode::findOrFail($id);

        // Hapus file QR
        if (file_exists(public_path($qr->path_qr))) {
            unlink(public_path($qr->path_qr));
        }

        $qr->delete();

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dihapus'
        ]);
    }
}
