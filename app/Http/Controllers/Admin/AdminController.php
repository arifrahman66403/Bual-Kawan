<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisPengunjung; 
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung statistik
        $tanggal_hari_ini = date('Y-m-d');
        
        $total_tamu_hari_ini = KisPengunjung::whereDate('tgl_kunjungan', $tanggal_hari_ini)
                                            ->where('status', 'disetujui')
                                            ->count();
        
        $total_tamu_semua = KisPengunjung::count();
        
        // Data 7 hari terakhir (untuk chart)
        $chart_data = KisPengunjung::selectRaw('DATE(tgl_kunjungan) as tanggal, COUNT(*) as total')
                                   ->where('status', 'disetujui')
                                   ->whereBetween('tgl_kunjungan', [now()->subDays(6), now()])
                                   ->groupBy('tanggal')
                                   ->get();
        
        return view('admin.dashboard', compact('total_tamu_hari_ini', 'total_tamu_semua', 'chart_data'));
    }

    public function pendingList()
    {
        // Menampilkan daftar pengajuan yang perlu disetujui
        $pending = KisPengunjung::where('status', 'pengajuan')->paginate(10);
        return view('admin.pengunjung.index', ['pengunjungList' => $pending, 'pageTitle' => 'Daftar Pengajuan']);
    }

    public function approvePengunjung($id)
    {
        $pengunjung = KisPengunjung::findOrFail($id);
        if ($pengunjung->status !== 'pengajuan') {
            return back()->with('error', 'Status kunjungan tidak lagi dalam pengajuan.');
        }

        $pengunjung->status = 'disetujui';
        $pengunjung->edited_by = Auth::id(); 
        $pengunjung->save();
        
        return back()->with('success', 'Pengajuan kunjungan berhasil disetujui.');
    }
    
    public function allPengunjung()
    {
        // Menampilkan semua data pengunjung
        $all = KisPengunjung::latest()->paginate(10);
        return view('admin.pengunjung.index', ['pengunjungList' => $all, 'pageTitle' => 'Data Master Pengunjung']);
    }

    // ... Tambahkan fungsi CRUD lainnya
}