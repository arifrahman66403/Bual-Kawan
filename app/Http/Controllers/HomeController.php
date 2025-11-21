<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}