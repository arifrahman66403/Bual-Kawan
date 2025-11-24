<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrackingExport;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // Menampilkan semua tracking
    public function index(Request $request)
    {
        // Ambil data tracking dengan relasi
        $query = KisTracking::with(['pengunjung', 'createdBy']);

        // --- Filter Waktu ---
        
        // Filter Minggu
        if ($request->has('minggu') && $request->minggu != '') {
            // WEEK(date, mode): Mode 1 dimulai Senin
            $query->whereRaw("WEEK(created_at, 1) = ?", [$request->minggu]);
        }

        // Filter Bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter Tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('created_at', $request->tahun);
        }

        // --- Filter Tipe Pengunjung ---
        
        if ($request->has('tipe') && $request->tipe != '') {
            $tipe = $request->tipe;

            // Filter berdasarkan relasi 'pengunjung'
            $query->whereHas('pengunjung', function ($q) use ($tipe) {
                // Pastikan variabel $tipe dilewatkan ke dalam closure dengan 'use'
                $q->where('tipe_pengunjung', $tipe);
            });
        }

        // Urutkan dan Paginate
        $trackings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pertahankan query string saat pagination (supaya filter tidak hilang saat pindah halaman)
        $trackings->appends($request->all());

        // Jika Anda butuh data lain (opsional)
        // $data = KisTracking::with('pengunjung')->get(); 

        return view('admin.riwayat', compact('trackings'));
    }
    /**
     * Mengekspor data riwayat tracking ke file Excel dengan filter.
     */
    public function exportTracking(Request $request)
    {
        // 1. Ambil input dan ubah ke Integer (int)
        // Jika kosong, biarkan null
        $bulan = $request->input('bulan') ? (int) $request->input('bulan') : null;
        $tahun = $request->input('tahun') ? (int) $request->input('tahun') : null;
        
        // Buat nama file yang dinamis
        $periode = 'Semua_Data';
        
        if ($bulan && $tahun) {
            // Karena $bulan sudah (int), Carbon akan menerimanya tanpa error
            $namaBulan = \Carbon\Carbon::create()->month($bulan)->locale('id')->monthName;
            $periode = $namaBulan . '_' . $tahun;
        } elseif ($tahun) {
            $periode = 'Tahun_' . $tahun;
        }

        $fileName = 'Riwayat_Tracking_' . $periode . '_' . date('Ymd_His') . '.xlsx';

        // Panggil Export Class dengan parameter yang sudah di-cast
        return Excel::download(new TrackingExport($bulan, $tahun), $fileName);
    }
}