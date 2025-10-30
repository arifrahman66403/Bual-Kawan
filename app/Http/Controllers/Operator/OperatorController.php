<?php
namespace App\Http\Controllers\Operator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisTracking;
use App\Models\KisPengunjung; 
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
        $request->validate(['kode_qr' => 'required|string']);
        $qrCode = $request->kode_qr;

        $pengunjung = KisPengunjung::where('kode_qr', $qrCode)->first();

        if (!$pengunjung) {
            return back()->with('error', 'Kode QR tidak valid atau tidak terdaftar.');
        }
        if ($pengunjung->status !== 'disetujui' && $pengunjung->status !== 'sedang kunjungan') {
             return back()->with('error', 'Pengunjung belum disetujui atau kunjungan sudah selesai.');
        }
        
        $tracking = KisTracking::where('pengunjung_id', $pengunjung->id)
                                ->whereDate('waktu_masuk', Carbon::today())
                                ->whereNull('waktu_keluar') 
                                ->first();

        if (!$tracking) {
            // CHECK-IN
            KisTracking::create([
                'pengunjung_id' => $pengunjung->id,
                'waktu_masuk' => Carbon::now(),
                'status' => 'di dalam', 
                'created_by' => Auth::id(),
            ]);
            $pengunjung->status = 'sedang kunjungan';
            $pengunjung->save();
            return back()->with('success', 'Check-in berhasil! Selamat datang.');
        } else {
            // CHECK-OUT
            $tracking->waktu_keluar = Carbon::now();
            $tracking->status = 'kunjungan selesai';
            $tracking->edited_by = Auth::id();
            $tracking->save();

            $pengunjung->status = 'kunjungan selesai';
            $pengunjung->save();
            return back()->with('success', 'Check-out berhasil! Terima kasih.');
        }
    }

    public function riwayatScan()
    {
        $riwayat = KisTracking::where('created_by', Auth::id())
                            ->with('pengunjung')
                            ->latest()
                            ->paginate(10);

        return view('operator.riwayat', compact('riwayat'));
    }
}