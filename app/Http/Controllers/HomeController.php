<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisPengunjung;  // Pastikan ada
use App\Models\Slider; // Pastikan nama model sudah benar
use App\Models\Gallery; // Ganti dengan App\Models\Galeri jika nama modelnya Galeri

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage) dengan data sliders dan galleries.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil data Sliders
        // Diasumsikan Anda hanya ingin slider yang aktif/telah terbit, 
        // dan diurutkan berdasarkan urutan atau tanggal terbaru.
        $sliders = Slider::latest() // Ganti 'order' jika Anda menggunakan kolom pengurutan lain
                          ->get();

        // 2. Ambil data Galleries (Galeri Foto)
        // Diasumsikan Anda hanya ingin galeri yang aktif/terbit, dan ambil 12 data terbaru
        $galleries = Gallery::latest() // Ambil yang terbaru
                             ->limit(12) // Batasi jumlah yang ditampilkan di halaman utama
                             ->get();

        // 3. Kirim data ke view
        return view('beranda', [
            'sliders' => $sliders,
            'galleries' => $galleries,
            'title' => 'Selamat Datang',
        ]);
    }

    // =========================================================================
    // 2. DASHBOARD & STATISTIK
    // =========================================================================
    public function infos()
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
        
        return view('statistik', compact(
            'total_tamu_hari_ini', 
            'total_tamu_bulan_ini', 
            'total_tamu_tahun_ini', 
            'total_tamu_semua', 
            'chart_data'
        ));
    }
}