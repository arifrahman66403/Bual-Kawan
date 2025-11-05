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
        // Ambil data kunjungan yang aktif (disetujui atau sedang kunjungan)
        $kunjunganAktif = KisPengunjung::whereIn('status', ['disetujui', 'sedang kunjungan'])
                                       ->latest('tgl_kunjungan')
                                       ->paginate(5);
                                       
        // Asumsi View Daftar Kunjungan Aktif Anda adalah 'guest.kunjungan_aktif'
        return view('guest.kunjungan_aktif', compact('kunjunganAktif'));
    }

    /**
     * Menampilkan Form Pengajuan Kunjungan (Form Tambah Kunjungan).
     */
    public function showCreateForm()
    {
        // Asumsi View Form Anda adalah 'guest.tambah_kunjungan'
        return view('guest.tambah_kunjungan');
    }

    /**
     * Memproses dan menyimpan data pengajuan kunjungan dari guest ke 3 tabel (Pengunjung, Dokumen, Peserta).
     */
    public function storeKunjungan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            // KisPengunjung fields
            'kode_daerah' => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tgl_kunjungan' => 'required|date',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email|max:255',
            'wa_perwakilan' => 'required|string|max:20',
            
            // File SPT (KisDokumen)
            'file_spt' => 'nullable|file|mimes:pdf|max:2048', 
            
            // KisPesertaKunjungan (Perwakilan sebagai peserta utama)
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
        ]);
        
        // Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // 2. Simpan Data KisPengunjung
            $pengunjung = KisPengunjung::create([
                'uid' => Str::uuid(), // Generate UID (PK)
                'kode_daerah' => $request->kode_daerah,
                'nama_instansi' => $request->nama_instansi,
                'satuan_kerja' => $request->satuan_kerja,
                'tujuan' => $request->tujuan,
                'tgl_kunjungan' => $request->tgl_kunjungan,
                'nama_perwakilan' => $request->nama_perwakilan,
                'email_perwakilan' => $request->email_perwakilan,
                'wa_perwakilan' => $request->wa_perwakilan,
                'status' => 'pengajuan', // Status awal: Pengajuan
                'kode_qr' => 'QR-' . Str::random(8), // Generate Kode QR
                'created_by' => null, // Guest
            ]);

            // Ambil UID Pengunjung untuk kunci asing
            $pengunjungUid = $pengunjung->uid;

            // Pengecekan keamanan (Walau jarang, ini menghentikan error 1048)
            if (empty($pengunjungUid)) {
                throw new \Exception("Kegagalan internal saat membuat ID pengunjung.");
            }
            
            // 3. Simpan File SPT (KisDokumen)
            if ($request->hasFile('file_spt')) {
                $file = $request->file('file_spt');
                $path = Storage::putFile('public/spt', $file); // Simpan file

                KisDokumen::create([
                    'pengunjung_id' => $pengunjungUid, // Kunci asing (UUID)
                    'file_spt' => $path, 
                    'created_by' => null, // Guest
                ]);
            }
            
            // 4. Simpan Data Peserta Kunjungan (Perwakilan Utama)
            KisPesertaKunjungan::create([
                'uid' => Str::uuid(), // Wajib: Generate UID (PK) untuk Peserta Kunjungan
                'pengunjung_id' => $pengunjungUid, // Kunci asing (UUID)
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email_perwakilan,
                'wa' => $request->wa_perwakilan,
                'created_by' => null, // Guest
            ]);

            DB::commit(); // Komit transaksi

            // 5. Redirect ke halaman index dengan pesan sukses
            return redirect()->route('guest.index')->with('success', 'Pengajuan kunjungan berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            
            // Log::error('Guest submission failed: ' . $e->getMessage()); // Opsional: Tambahkan Log
            
            // Tampilkan error (Untuk debugging)
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Debug: ' . $e->getMessage());
        }
    }
    
    public function showDetail($id)
    {
        // Temukan KisPengunjung berdasarkan UID dan muat relasi peserta dan dokumen
        $pengunjung = KisPengunjung::where('uid', $id)
                                    ->with(['peserta', 'dokumen'])
                                    ->firstOrFail();
                                    
        return view('guest.detail', compact('pengunjung'));
    }
}