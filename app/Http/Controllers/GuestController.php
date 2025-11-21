<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisPengunjung;
use App\Models\KisDokumen;
use App\Models\KisPesertaKunjungan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * Menampilkan Daftar Kunjungan Aktif (Landing Page).
     */
    public function index()
    {
        // Menggunakan view namespace 'kunjungan' sesuai preferensi Anda
        $kunjunganAktif = KisPengunjung::whereIn('status', ['disetujui', 'kunjungan', 'selesai'])
                                    ->with('qrCode')
                                    ->latest()
                                    ->paginate(5);
                                       
        return view('kunjungan.kunjungan_aktif', [
            'kunjunganAktif' => $kunjunganAktif,
            'title' => 'Daftar',
            'breadcrumb' => 'Daftar Kunjungan'
        ]);
    }

    /**
     * Menampilkan detail kunjungan untuk tampilan publik/user.
     * @param string $id UID dari KisPengunjung
     * @return \Illuminate\View\View
     */
    public function showDetail($id)
    {
        try {
            // 1. Ambil data kunjungan dengan relasi dokumen dan peserta.
            // Data Perwakilan diambil dari kolom utama KisPengunjung.
            $pengunjung = KisPengunjung::where('uid', $id)
                ->with(['peserta', 'dokumen']) 
                ->firstOrFail();

            // 2. Siapkan Objek Data Perwakilan ($perwakilanPeserta)
            // Data perwakilan diambil langsung dari kolom utama model KisPengunjung.
            $perwakilanPeserta = (object) [
                'nama' => $pengunjung->nama_perwakilan,
                'email' => $pengunjung->email_perwakilan,
                'wa' => $pengunjung->wa_perwakilan,
                'jabatan' => $pengunjung->jabatan_perwakilan, // diambil dari kolom jabatan di KisPengunjung
                'nip' => $pengunjung->nip_perwakilam,         // diambil dari kolom nip di KisPengunjung
            ];


            // 3. Siapkan Koleksi Anggota Rombongan ($anggotaRombongan)
            // Karena kolom 'is_perwakilan' diabaikan/dihapus, kita asumsikan 
            // SEMUA data di relasi 'peserta' adalah anggota rombongan tambahan.
            // Kita tidak perlu lagi memfilter.
            $anggotaRombongan = $pengunjung->peserta; 

            // 4. Kirim data yang dibutuhkan ke View
            return view('kunjungan.detail', [
                'perwakilanPeserta' => $perwakilanPeserta, // Objek Data Perwakilan (dari KisPengunjung)
                'anggotaRombongan' => $anggotaRombongan, // Koleksi Rombongan (Seluruh relasi peserta)
                'pengunjung' => $pengunjung, // Data utama (Instansi, Tanggal, Status, Dokumen, QR)
                'title' => 'Detail Kunjungan',
            ]);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('kunjungan.index')->with('error', 'Kunjungan tidak ditemukan atau URL tidak valid.');
        }
    }
}    