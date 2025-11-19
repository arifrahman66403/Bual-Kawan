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

        // validasi masa berlaku QR
        if ($qr) {
            $now = Carbon::now();
            if (($qr->berlaku_mulai && $now->lt(Carbon::parse($qr->berlaku_mulai))) ||
                ($qr->berlaku_sampai && $now->gt(Carbon::parse($qr->berlaku_sampai)))) {
                return back()->with('error', 'Kode QR tidak berlaku (kedaluwarsa).');
            }
        }

        if (! $qr || ! $qr->pengunjung) {
            return back()->with('error', 'Kode QR tidak valid atau tidak terdaftar.');
        }

        $pengunjung = $qr->pengunjung;

        if (! in_array($pengunjung->status, ['disetujui', 'kunjungan'])) {
            return back()->with('error', 'Pengunjung belum disetujui atau kunjungan sudah selesai.');
        }

        // Cari tracking aktif hari ini (status 'kunjungan')
        $active = KisTracking::where('pengajuan_id', $pengunjung->uid)
                    ->where('status', 'kunjungan')
                    ->whereDate('created_at', Carbon::today())
                    ->first();

        if (! $active) {
            // CHECK-IN
            $tracking = new KisTracking();
            $tracking->pengajuan_id = $pengunjung->uid;
            $tracking->catatan = 'Check-in oleh operator';
            $tracking->status = 'kunjungan';
            $tracking->created_by = Auth::id();
            $tracking->save();

            $pengunjung->status = 'kunjungan';
            $pengunjung->save();

            return back()->with('success', 'Check-in berhasil! Selamat datang.');
        }

        // CHECK-OUT (update record aktif)
        $active->catatan = 'Check-out oleh operator';
        $active->status = 'selesai';
        $active->edited_by = Auth::id();
        $active->save();

        $pengunjung->status = 'selesai';
        $pengunjung->save();

        return back()->with('success', 'Check-out berhasil!');
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