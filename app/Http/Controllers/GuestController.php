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
        // 1. Validasi Data (DITAMBAH: Validasi untuk array Peserta)
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
            'jabatan' => 'required|string|max:255', // Jabatan Perwakilan
            'nip' => 'nullable|string|max:255',     // NIP Perwakilan

            // --- VALIDASI TAMBAHAN UNTUK PESERTA DINAMIS ---
            'peserta_nama.*' => 'nullable|string|max:255',
            'peserta_jabatan.*' => 'nullable|string|max:255',
            'peserta_kontak.*' => 'nullable|string|max:20',
            // Catatan: Tanda .* memastikan validasi diterapkan ke setiap elemen array.
        ]);
        
        DB::beginTransaction();

        try {
            // 2. Simpan Data KisPengunjung
            // Menggunakan UUID untuk UID yang kuat.
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
                $path = Storage::disk('public')->putFile('spt', $file); 

                KisDokumen::create([
                    'uid' => Str::uuid(), 
                    'pengunjung_id' => $pengunjungUid, 
                    'file_spt' => $path, 
                    'created_by' => null, 
                ]);
            }
            
            // --- LOGIKA PESERTA: Kumpulkan semua peserta (Perwakilan + Rombongan) ---
            $peserta_data_massal = [];

            // 4a. Tambahkan Perwakilan Utama (Peserta ke-1)
            $peserta_data_massal[] = [
                'uid' => Str::uuid(),
                'pengunjung_id' => $pengunjungUid, 
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'email' => $request->email_perwakilan,
                'wa' => $request->wa_perwakilan,
                'created_by' => null,
                'created_at' => now(), // Penting untuk insert massal
                'updated_at' => now(),
            ];

            // 4b. Tambahkan Peserta Rombongan Tambahan (Looping Array)
            if ($request->has('peserta_nama') && is_array($request->peserta_nama)) {
                $nama_array = $request->peserta_nama;
                $jabatan_array = $request->peserta_jabatan;
                $kontak_array = $request->peserta_kontak; // Menggabungkan NIP/WA
                
                $count = count($nama_array);

                for ($i = 0; $i < $count; $i++) {
                    $nama = trim($nama_array[$i] ?? '');
                    
                    // Hanya proses jika nama tidak kosong (karena input required di form)
                    if (!empty($nama)) {
                        $peserta_data_massal[] = [
                            'uid' => Str::uuid(),
                            'pengunjung_id' => $pengunjungUid, 
                            'nama' => $nama,
                            'nip' => Str::isUuid($kontak_array[$i] ?? '') ? ($kontak_array[$i] ?? null) : null, // Asumsi kontak adalah NIP jika formatnya seperti NIP
                            'jabatan' => $jabatan_array[$i] ?? null,
                            'email' => null, // Email tidak ada di form rombongan
                            'wa' => !Str::isUuid($kontak_array[$i] ?? '') ? ($kontak_array[$i] ?? null) : null, // Asumsi kontak adalah WA jika bukan NIP
                            'created_by' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            
            // 5. Insert Massal Peserta Kunjungan (Termasuk Perwakilan + Rombongan)
            if (!empty($peserta_data_massal)) {
                KisPesertaKunjungan::insert($peserta_data_massal);
            }
            
            // Catatan: Tambahkan logika tracking di sini jika Anda memilikinya.

            DB::commit(); 
            return redirect()->route('kunjungan.index')->with('success', 'Pengajuan kunjungan berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');

        } catch (\Exception $e) {
            DB::rollBack(); 
            // Hapus file yang terupload jika transaction gagal
            if (isset($path)) {
                Storage::disk('public')->delete($path);
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
        
        $perwakilanPeserta = KisPesertaKunjungan::where('pengunjung_id', $pengunjung->uid)
                                    ->where('nama', $pengunjung->nama_perwakilan)
                                    ->first();

        return view('kunjungan.detail', [
            'perwakilanPeserta' => $perwakilanPeserta,
            'pengunjung' => $pengunjung,
            'title' => 'Detail pengunjung'
        ]);
    }

}