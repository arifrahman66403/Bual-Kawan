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
    public function index()
    {
        $trackings = \App\Models\KisTracking::with('pengunjung')->latest()->get();
        return view('admin.riwayat', compact('trackings'));

        $data = KisTracking::with('pengunjung')->get();
        return response()->json($data);

    }
    /**
     * Mengekspor data riwayat tracking ke file Excel dengan filter.
     */
    public function exportTracking(Request $request)
    {
        // Ambil input bulan dan tahun dari request
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        
        // Buat nama file yang dinamis
        $periode = 'Semua_Data';
        if ($bulan && $tahun) {
            $namaBulan = \Carbon\Carbon::create()->month($bulan)->locale('id')->monthName;
            $periode = $namaBulan . '_' . $tahun;
        } elseif ($tahun) {
            $periode = 'Tahun_' . $tahun;
        }

        $fileName = 'Riwayat_Tracking_' . $periode . '_' . date('Ymd_His') . '.xlsx';

        // Panggil Export Class dengan parameter
        return Excel::download(new TrackingExport($bulan, $tahun), $fileName);
    }
}