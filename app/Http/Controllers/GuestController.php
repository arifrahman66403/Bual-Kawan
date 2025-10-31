<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\KisDokumen;
use App\Models\KisPesertaKunjungan;
use App\Models\KisPengunjung;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * Menampilkan Daftar Kunjungan Aktif (Landing Page).
     */
    public function index()
    {
        // Ambil data kunjungan yang aktif (misalnya, status 'disetujui' atau 'sedang kunjungan')
        $kunjunganAktif = KisPengunjung::whereIn('status', ['disetujui', 'sedang kunjungan'])
                                       ->latest('tgl_kunjungan')
                                       ->paginate(5);
                                       
        return view('guest.kunjungan_aktif', compact('kunjunganAktif'));
    }

    /**
     * Menampilkan Form Pengajuan Kunjungan (Form Tambah Kunjungan).
     */
    public function showCreateForm()
    {
        return view('guest.tambah_kunjungan');
    }

    /**
     * Memproses dan menyimpan data pengajuan kunjungan dari guest.
     */
    public function storeKunjungan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            // KisPengunjung fields
            'kode_daerah' => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255', // Diasumsikan kolom ini ada
            'tgl_kunjungan' => 'required|date',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email|max:255',
            'wa_perwakilan' => 'required|string|max:20',
            
            // File SPT (KisDokumen)
            'file_spt' => 'nullable|file|mimes:pdf|max:2048', // Maks 2MB, hanya PDF
            
            // KisPesertaKunjungan (Perwakilan sebagai peserta utama)
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
        ]);
        
        // Mulai Transaksi Database
        DB::beginTransaction();

         try {
            // 2. Simpan Data KisPengunjung
            $pengunjung = KisPengunjung::create([
                'uid' => Str::uuid(),
                // ... (data pengunjung)
                'kode_daerah' => $request->kode_daerah,
                'nama_instansi' => $request->nama_instansi,
                'satuan_kerja' => $request->satuan_kerja,
                'tujuan' => $request->tujuan,
                'tgl_kunjungan' => $request->tgl_kunjungan,
                'nama_perwakilan' => $request->nama_perwakilan,
                'email_perwakilan' => $request->email_perwakilan,
                'wa_perwakilan' => $request->wa_perwakilan,
                'status' => 'pengajuan',
                'kode_qr' => 'QR-' . Str::random(8), 
                'created_by' => null, // Guest
                // Pastikan semua kolom NOT NULL diisi atau diizinkan NULL
            ]);

            // PENTING: Lakukan pengecekan UID sebelum digunakan
            $pengunjungUid = $pengunjung->uid;

            if (empty($pengunjungUid)) {
                // Jika UID gagal terambil, hentikan transaksi
                throw new \Exception("Gagal mengambil UID pengunjung yang baru dibuat.");
            }

            // 3. Simpan File SPT (KisDokumen)
            if ($request->hasFile('file_spt')) {
                // Pastikan direktori 'public/spt' sudah ada atau dapat dibuat
                $file = $request->file('file_spt');
                // Menggunakan putFile untuk penamaan file yang lebih aman
                $path = Storage::putFile('public/spt', $file); 
            
            KisDokumen::create([
                    'pengunjung_id' => $pengunjungUid, // Menggunakan ID auto-increment yang baru dibuat
                    'file_spt' => $path, // Simpan path internal storage
                    'created_by' => null, // Guest
                ]);
            }
            
            // 4. Simpan Data Peserta Kunjungan (Perwakilan Utama)
            KisPesertaKunjungan::create([
                'pengunjung_id' => $pengunjungUid, // Kunci asing harus menggunakan ID auto-increment
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email_perwakilan,
                'wa' => $request->wa_perwakilan,
                'created_by' => null, // Guest
            ]);

            DB::commit(); // Komit transaksi
            
            // Hapus baris debugging jika ada
            // dd("Berhasil disimpan!"); 

            return redirect()->route('guest.index')->with('success', 'Pengajuan kunjungan Anda berhasil dikirim dan menunggu verifikasi Admin.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
        // BARIS DEBUGGING (HAPUS BARIS INI SETELAH TESTING)
            // dd($e->getMessage()); 
            
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Error: ' . $e->getMessage());
        }
    }
}