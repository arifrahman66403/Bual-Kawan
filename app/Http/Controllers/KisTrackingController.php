<?php

namespace App\Http\Controllers;

use App\Models\KisTracking;
use App\Models\KisQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KisTrackingController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('superadmin')) {
            $trackings = KisTracking::with('pengunjung')->latest()->get();
        } elseif (Auth::user()->hasRole('admin')) {
            $trackings = KisTracking::with('pengunjung')
                ->where('pengajuan_id', Auth::user()->pengajuan_id)
                ->latest()
                ->get();
        } else {
            $trackings = KisTracking::with('pengunjung')
                ->whereDate('created_at', Carbon::today())
                ->latest()
                ->get();
        }

        return view('tracking.index', compact('trackings'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string',
        ]);

        // 🔍 Cek QR Code valid
        $qr = KisQrCode::where('qr_code', $request->kode_qr)->first();

        if (!$qr) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak ditemukan',
            ], 404);
        }

        // 🚨 Cek apakah pengajuan_id ada
        if (empty($qr->pengajuan_id)) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak memiliki pengajuan_id yang valid',
            ], 422);
        }

        // 🕒 Ambil atau buat tracking baru
        $tracking = KisTracking::firstOrNew(['pengajuan_id' => $qr->pengajuan_id]);

        // 💡 Update status
        if (!$tracking->exists || $tracking->status == 'disetujui') {
            $tracking->status = 'kunjungan';
            $tracking->created_at = Carbon::now();
        } elseif ($tracking->status == 'kunjungan') {
            $tracking->status = 'selesai';
            $tracking->updated_at = Carbon::now();
        }

        // 🩵 Pastikan pengajuan_id tetap terisi
        $tracking->pengajuan_id = $qr->pengajuan_id;

        // Simpan perubahan
        $tracking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status kunjungan diperbarui menjadi ' . $tracking->status,
            'data' => $tracking,
        ]);
    }
}
