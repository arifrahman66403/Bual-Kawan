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
        // Contoh statistik untuk Dashboard Admin
        $pengajuanCount = KisPengunjung::where('status', 'pengajuan')->count();
        return view('admin.dashboard', compact('pengajuanCount'));
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