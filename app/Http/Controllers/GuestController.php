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
        $kunjunganAktif = KisPengunjung::whereIn('status', ['pengajuan', 'disetujui', 'kunjungan'])
                                       ->latest('tgl_kunjungan')
                                       ->paginate(5);
                                       
        return view('kunjungan.kunjungan_aktif', [
            'kunjunganAktif' => $kunjunganAktif,
            'title' => 'Daftar',
            'breadcrumb' => 'Daftar Kunjungan'
        ]);
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
        // 1. Validasi Data (DITAMBAH: Validasi array Email)
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

            // --- VALIDASI TAMBAHAN UNTUK PESERTA DINAMIS ---
            'peserta_nama.*' => 'nullable|string|max:255',
            'peserta_jabatan.*' => 'nullable|string|max:255',
            'peserta_kontak.*' => 'nullable|string|max:20',
            'peserta_email.*' => 'nullable|email|max:255', // <-- VALIDASI EMAIL BARU
            'peserta_ttd.*' => 'nullable|file|mimes:jpg,png|max:1024', 
        ]);
        
        DB::beginTransaction();
        $spt_path = null; 
        $ttd_files = $request->file('peserta_ttd');
        
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

            // 3. Simpan File SPT (KisDokumen) - LOGIKA SAMA
            if ($request->hasFile('file_spt')) {
                $spt_file = $request->file('file_spt');
                $spt_path = Storage::disk('public')->putFile('spt', $spt_file);

                KisDokumen::create([
                    'uid' => Str::uuid(), 
                    'pengunjung_id' => $pengunjungUid, 
                    'file_spt' => $spt_path, 
                    'created_by' => null, 
                ]);
            }
            
            // --- LOGIKA PESERTA: Kumpulkan semua peserta ---
            $peserta_data_massal = [];

            // 4a. Tambahkan Perwakilan Utama (Peserta ke-1)
            // Menggunakan data Perwakilan dari field utama form
            $peserta_data_massal[] = [
                'uid' => Str::uuid(),
                'pengunjung_id' => $pengunjungUid, 
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email_perwakilan, // <-- Mengambil Email Perwakilan
                'wa' => $request->wa_perwakilan,
                'file_ttd' => null, 
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 4b. Tambahkan Peserta Rombongan Tambahan (Looping Array)
            if ($request->has('peserta_nama') && is_array($request->peserta_nama)) {
                $nama_array = $request->peserta_nama;
                $jabatan_array = $request->peserta_jabatan;
                $kontak_array = $request->peserta_kontak;
                $email_array = $request->peserta_email; // <-- AMBIL ARRAY EMAIL BARU
                
                $count = count($nama_array);

                for ($i = 0; $i < $count; $i++) {
                    $nama = trim($nama_array[$i] ?? '');
                    
                    if (!empty($nama)) {
                        $ttd_path = null;
                        
                        if (isset($ttd_files[$i]) && $ttd_files[$i] instanceof \Illuminate\Http\UploadedFile) {
                            $ttd_path = Storage::disk('public')->putFile('ttd_peserta', $ttd_files[$i]); 
                        }
                        
                        $peserta_data_massal[] = [
                            'uid' => Str::uuid(),
                            'pengunjung_id' => $pengunjungUid, 
                            'nama' => $nama,
                            'nip' => $kontak_array[$i] ?? null, 
                            'jabatan' => $jabatan_array[$i] ?? null,
                            'email' => $email_array[$i] ?? null, // <-- SIMPAN EMAIL ROMBONGAN
                            'wa' => $kontak_array[$i] ?? null, 
                            'file_ttd' => $ttd_path, 
                            'created_by' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            
            // 5. Insert Massal Peserta Kunjungan 
            if (!empty($peserta_data_massal)) {
                KisPesertaKunjungan::insert($peserta_data_massal);
            }

            DB::commit(); 
            return redirect()->route('kunjungan.index')->with('success', 'Pengajuan kunjungan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            if (isset($spt_path)) {
                Storage::disk('public')->delete($spt_path);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Pesan Error: ' . $e->getMessage());
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

        return view('kunjungan.detail', [
            'pengunjung' => $pengunjung,
            'title' => 'Detail pengunjung'
        ]);
    }

}