<?php

namespace App\Http\Controllers;

use App\Models\KisTracking;
use App\Models\KisQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KisTrackingController extends Controller
{
    /**
     * Menampilkan daftar tracking (khusus role tertentu)
     */
    public function index()
    {
        // Superadmin bisa lihat semua
        if (Auth::user()->hasRole('superadmin')) {
            $trackings = KisTracking::with('pengunjung')->latest()->get();
        }
        // Admin hanya lihat tracking di unitnya
        elseif (Auth::user()->hasRole('admin')) {
            $trackings = KisTracking::with('pengunjung')
                ->where('unit_id', Auth::user()->unit_id)
                ->latest()
                ->get();
        }
        // Operator hanya lihat tracking aktif hari ini
        else {
            $trackings = KisTracking::with('pengunjung')
                ->whereDate('created_at', Carbon::today())
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $trackings,
        ]);
    }

    /**
     * Proses scan QR Code untuk update status kunjungan
     */
    public function scan(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string'
        ]);

        // 🔍 Cek QR Code valid
        $qr = KisQrCode::where('kode_qr', $request->kode_qr)->first();

        if (!$qr) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak ditemukan'
            ], 404);
        }

        // 🕒 Ambil atau buat tracking baru
        $tracking = KisTracking::firstOrNew(['pengunjung_id' => $qr->pengunjung_id]);

        // 💡 Update status berdasarkan kondisi
        if (!$tracking->exists || $tracking->status == 'disetujui') {
            $tracking->status = 'kunjungan';
            $tracking->waktu_masuk = Carbon::now();
        } elseif ($tracking->status == 'kunjungan') {
            $tracking->status = 'selesai';
            $tracking->waktu_keluar = Carbon::now();
        }

        // Simpan perubahan
        $tracking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status kunjungan diperbarui menjadi ' . $tracking->status,
            'data' => $tracking
        ]);
    }
}
