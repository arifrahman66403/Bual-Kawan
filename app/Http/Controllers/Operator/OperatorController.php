<?php

namespace App\Http\Controllers\Operator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisTracking;
use App\Models\KisPengunjung;
use App\Models\KisQrCode; // added
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function index()
    {
        return view('operator.dashboard'); 
    }
    
    public function processScan(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $qrCode = $request->qr_code;

        $qr = KisQrCode::where('qr_code', $qrCode)
                ->with('pengunjung')
                ->first();

        if (! $qr || ! $qr->pengunjung) {
            return back()->with('error', 'Kode QR tidak valid atau tidak terdaftar.');
        }

        $pengunjung = $qr->pengunjung;

        if (! in_array($pengunjung->status, ['disetujui', 'kunjungan'])) {
            return back()->with('error', 'Pengunjung belum disetujui atau kunjungan sudah selesai.');
        }

        // cari record hari ini yang belum checkout (edited_by null)
        $tracking = KisTracking::where('pengajuan_id', $pengunjung->uid)
                    ->whereDate('created_at', Carbon::today())
                    ->whereNull('edited_by')
                    ->first();

        if (! $tracking) {
            // CHECK-IN
            $tracking = new KisTracking();
            $tracking->pengajuan_id = $pengunjung->uid;
            $tracking->catatan = 'Sedang kunjungan';
            $tracking->status = 'kunjungan';
            $tracking->created_by = Auth::id(); // simpan user yang melakukan check-in
            $tracking->save();

            $pengunjung->status = 'kunjungan';
            $pengunjung->save();

            return back()->with('success', 'Check-in berhasil! Selamat datang.');
        } else {
            // CHECK-OUT
            $tracking->catatan = 'Kunjungan selesai';
            $tracking->status = 'selesai';
            $tracking->edited_by = Auth::id(); // simpan user yang melakukan check-out
            $tracking->save();

            $pengunjung->status = 'selesai';
            $pengunjung->save();

            return back()->with('success', 'Check-out berhasil!');
        }
    }

    public function riwayatScan()
    {
        $riwayat = KisTracking::with('pengunjung')
                    ->where('created_by', Auth::id())
                    ->whereDate('created_at', Carbon::today())
                    ->orderByDesc('created_at')
                    ->get();

        return view('operator.riwayat', compact('riwayat'));
    }
}