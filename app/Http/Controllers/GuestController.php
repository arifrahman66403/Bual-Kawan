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
        $kunjunganAktif = KisPengunjung::whereIn('status', ['disetujui', 'kunjungan'])
                                       ->latest('tgl_kunjungan')
                                       ->paginate(5);
                                       
        return view('kunjungan.kunjungan_aktif', compact('kunjunganAktif'));
    }

    /**
     * Menampilkan Form Pengajuan Kunjungan (Form Tambah Kunjungan).
     */
    public function showCreateForm()
    {
        return view('kunjungan.tambah_kunjungan');
    }

    /**
     * Memproses dan menyimpan data pengajuan kunjungan ke 3 tabel.
     */
    public function storeKunjungan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'kode_daerah' => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tgl_kunjungan' => 'required|date',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email|max:255',
            'wa_perwakilan' => 'required|string|max:20',
            'file_spt' => 'nullable|file|mimes:pdf|max:2048', 
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();

        try {
            // 2. Simpan Data KisPengunjung
            $pengunjung = KisPengunjung::create([
                'uid' => Str::uuid(), 
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
                'created_by' => null,
            ]);

            $pengunjungUid = $pengunjung->uid;

            // 3. Simpan File SPT (KisDokumen)
            if ($request->hasFile('file_spt')) {
                $file = $request->file('file_spt');
                $path = Storage::disk('public')->putFile('spt', $file); // Ubah ke disk('public') 

                KisDokumen::create([
                    'uid' => Str::uuid(), // FIX: Tambahkan UID untuk KisDokumen
                    'pengunjung_id' => $pengunjungUid, 
                    'file_spt' => $path, 
                    'created_by' => null, 
                ]);
            }
            
            // 4. Simpan Data Peserta Kunjungan (Perwakilan Utama)
            KisPesertaKunjungan::create([
                'uid' => Str::uuid(), // Wajib: Generate UID (PK) untuk Peserta Kunjungan
                'pengunjung_id' => $pengunjungUid, 
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email_perwakilan,
                'wa' => $request->wa_perwakilan,
                'created_by' => null, 
            ]);

            DB::commit(); 
            return redirect()->route('kunjungan.index')->with('success', 'Pengajuan kunjungan berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Debug: ' . $e->getMessage());
        }
    }
    
    /**
     * Menampilkan Detail Laporan Kunjungan.
     */
    public function showDetail($id)
    {
        // FIX: Hapus 'file_spt' dari array with()
        $pengunjung = KisPengunjung::where('uid', $id)
                                    ->with(['peserta', 'dokumen'])
                                    ->firstOrFail();
                                    
        return view('kunjungan.detail', compact('pengunjung'));
    }

    // ==========================================================
    // LOGIKA TAMBAH PESERTA ROMBONGAN (SETELAH PENGAJUAN)
    // ==========================================================

    /**
     * Menampilkan form untuk menambah anggota rombongan.
     * @param string $id (UID KisPengunjung)
     */
    public function showAddPesertaForm($id)
    {
        $pengunjung = KisPengunjung::where('uid', $id)->firstOrFail();
        
        // Asumsi View Anda adalah 'kunjungan.tambah_peserta'
        return view('kunjungan.tambah_peserta', compact('pengunjung'));
    }

    /**
     * Memproses penyimpanan anggota rombongan baru.
     * @param Request $request
     * @param string $id (UID KisPengunjung)
     */
    public function storePeserta(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'wa' => 'required|string|max:20',
        ]);

        $pengunjung = KisPengunjung::where('uid', $id)->firstOrFail();
        $pengunjungUid = $pengunjung->uid;

        try {
            KisPesertaKunjungan::create([
                'uid' => Str::uuid(), // Wajib: Generate UID (PK)
                'pengunjung_id' => $pengunjungUid, 
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email,
                'wa' => $request->wa,
                'created_by' => null, 
            ]);

            return redirect()->route('kunjungan.detail', $pengunjungUid)->with('success', 'Anggota rombongan berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan peserta. Debug: ' . $e->getMessage());
        }
    }
}