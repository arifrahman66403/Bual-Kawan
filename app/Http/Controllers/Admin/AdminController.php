<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisPengunjung; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahan penting untuk password
use Illuminate\Support\Facades\Storage; // Tambahan penting untuk upload foto

class AdminController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD & STATISTIK
    // =========================================================================
    public function index()
    {
        $tanggal_hari_ini = date('Y-m-d');
        $bulan_tahun_ini = date('Y-m');
        $tahun_ini = date('Y');
        
        $total_tamu_hari_ini = KisPengunjung::whereDate('tgl_kunjungan', $tanggal_hari_ini)
                                            ->whereIn('status', ['disetujui', 'kunjungan', 'selesai'])
                                            ->count();
        
        $total_tamu_bulan_ini = KisPengunjung::whereRaw("DATE_FORMAT(tgl_kunjungan, '%Y-%m') = ?", [$bulan_tahun_ini])
                                             ->whereIn('status', ['disetujui', 'kunjungan', 'selesai'])
                                             ->count();
        
        $total_tamu_tahun_ini = KisPengunjung::whereYear('tgl_kunjungan', $tahun_ini)
                                             ->whereIn('status', ['disetujui', 'kunjungan', 'selesai'])
                                             ->count();
        
        $total_tamu_semua = KisPengunjung::count();
        
        // Data 7 hari terakhir (untuk chart)
        $chart_data = KisPengunjung::selectRaw('DATE(tgl_kunjungan) as tanggal, COUNT(*) as total')
                                   ->where('status', 'disetujui')
                                   ->whereBetween('tgl_kunjungan', [now()->subDays(6), now()])
                                   ->groupBy('tanggal')
                                   ->get();
        
        return view('admin.dashboard', compact(
            'total_tamu_hari_ini', 
            'total_tamu_bulan_ini', 
            'total_tamu_tahun_ini', 
            'total_tamu_semua', 
            'chart_data'
        ));
    }

    // =========================================================================
    // 2. MANAJEMEN PENGUNJUNG
    // =========================================================================
    public function pendingList()
    {
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
        // Pastikan kolom edited_by ada di tabel database kamu, jika tidak hapus baris ini
        $pengunjung->edited_by = Auth::id(); 
        $pengunjung->save();
        
        return back()->with('success', 'Pengajuan kunjungan berhasil disetujui.');
    }
    
    public function allPengunjung()
    {
        $all = KisPengunjung::latest()->paginate(10);
        return view('admin.pengunjung.index', ['pengunjungList' => $all, 'pageTitle' => 'Data Master Pengunjung']);
    }

    // =========================================================================
    // 3. MANAJEMEN PROFIL (BARU DITAMBAHKAN)
    // =========================================================================

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        return view('admin.profile.profile');
    }

    /**
     * Update data diri (Nama, Email, User, WA)
     * SESUAI SCHEMA: kis_user
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Asumsi model User mengarah ke kis_user

        // 1. Validasi
        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:kis_user,email,' . $user->id,
            'user'  => 'required|string|max:50|unique:kis_user,user,' . $user->id,
            'wa'    => 'nullable|string|max:20',
        ]);

        // 2. Simpan Data Sesuai Kolom Database
        $user->nama  = $request->nama;
        $user->email = $request->email;
        $user->user  = $request->user; // Update username
        $user->wa    = $request->wa;   // Update WhatsApp

        // Tidak ada logic upload avatar karena tidak ada kolomnya di DB
        
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Ganti Password
     * SESUAI SCHEMA: kolom 'pass'
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed|different:current_password',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'new_password.min'       => 'Password minimal 8 karakter.'
        ]);

        $user = Auth::user();

        // PENTING: Cek kecocokan password menggunakan kolom 'pass'
        // Pastikan di Model User kamu sudah override getAuthPassword (lihat poin 3 di bawah)
        if (!Hash::check($request->current_password, $user->pass)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update kolom 'pass'
        $user->pass = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}