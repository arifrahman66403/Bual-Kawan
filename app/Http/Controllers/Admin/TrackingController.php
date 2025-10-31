<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisTracking;
use App\Models\KisPengunjung;

class TrackingController extends Controller
{
    // Menampilkan semua tracking
    public function index()
    {
        $trackings = \App\Models\KisTracking::with('pengunjung')->latest()->get();
        return view('admin.index', compact('trackings'));

        $data = KisTracking::with('pengunjung')->get();
        return response()->json($data);
    }

    // Tambahkan catatan tracking baru
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required',
            'catatan' => 'required',
            'status' => 'required|in:disetujui,kunjungan,selesai'
        ]);

        $tracking = KisTracking::create([
            'pengajuan_id' => $request->pengajuan_id,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'created_by' => auth()->id() ?? 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil ditambahkan',
            'data' => $tracking
        ]);
    }

    // Update status tracking
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,kunjungan,selesai',
        ]);

        $pengunjung = KisPengunjung::findOrFail($id);
        $pengunjung->update(['status' => $request->status]);

        // Simpan ke tracking
        KisTracking::create([
            'pengajuan_id' => $pengunjung->uid,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
        ]);

        // === Jika disetujui, buat QR Code otomatis ===
        if ($request->status === 'disetujui') {
            $qrString = 'SINGGAH-' . strtoupper(Str::random(10));
            $fileName = 'qr_' . $pengunjung->uid . '.png';
            $filePath = 'public/qr_codes/' . $fileName;

            // Buat QR Code dan simpan ke storage
            QrCode::format('png')->size(250)->generate($qrString, storage_path('app/' . $filePath));

            KisQrCode::updateOrCreate(
                ['pengunjung_id' => $pengunjung->uid],
                [
                    'qr_code' => 'storage/qr_codes/' . $fileName,
                    'berlaku_mulai' => Carbon::now(),
                    'berlaku_sampai' => Carbon::now()->addDays(1),
                    'created_by' => Auth::user()->id,
                ]
            );
        }

        // === Catat Log Aktivitas ===
        KisLog::create([
            'user_id' => Auth::user()->id,
            'pengunjung_id' => $pengunjung->uid,
            'aksi' => 'Mengubah status pengunjung menjadi ' . $request->status,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil diperbarui',
            'data' => $tracking
        ]);
    }

    // Hapus data tracking
    public function destroy($id)
    {
        $tracking = KisTracking::findOrFail($id);
        $tracking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil dihapus'
        ]);
    }
}
