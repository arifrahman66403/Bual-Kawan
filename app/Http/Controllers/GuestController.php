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
        $kunjunganAktif = KisPengunjung::whereIn('status', ['pengajuan', 'disetujui', 'kunjungan', 'selesai'])
                                       ->latest()
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
            'jabatan_perwakilan' => 'required|string|max:255',
            'nip_perwakilan' => 'nullable|string|max:255',

            // peserta dinamis
            'peserta_nama.*' => 'nullable|string|max:255',
            'peserta_jabatan.*' => 'nullable|string|max:255',
            'peserta_kontak.*' => 'nullable|string|max:20',
            'peserta_email.*' => 'nullable|email|max:255',
            'peserta_ttd.*' => 'nullable|file|mimes:jpg,png|max:1024',
        ]);

        DB::beginTransaction();
        $spt_path = null;
        $ttd_files = $request->file('peserta_ttd');

        try {
            // 2. Simpan Data KisPengunjung (simpan data perwakilan di table pengunjung)
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
                'jabatan' => $request->jabatan_perwakilan,        // simpan jabatan perwakilan ke kolom jabatan
                'nip' => $request->nip_perwakilan,                // simpan nip perwakilan ke kolom nip
                'status' => 'pengajuan',
                'kode_qr' => 'QR-' . Str::random(8),
                'created_by' => null,
            ]);
            $pengunjungUid = $pengunjung->uid;

            // 3. Simpan File SPT
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

            // 4. Siapkan peserta: pertama adalah perwakilan (jelas terpisah)
            $peserta_data_massal = [];

            $peserta_data_massal[] = [
                'uid' => Str::uuid(),
                'pengunjung_id' => $pengunjungUid,
                'nama' => $request->nama_perwakilan,
                'nip' => $request->nip_perwakilan ?? null,
                'jabatan' => $request->jabatan_perwakilan ?? null,
                'email' => $request->email_perwakilan ?? null,
                'wa' => $request->wa_perwakilan ?? null,
                'file_ttd' => null,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 5. Tambahkan peserta rombongan tambahan dari array tanpa menimpa perwakilan
            if ($request->has('peserta_nama') && is_array($request->peserta_nama)) {
                $nama_array = $request->peserta_nama;
                $jabatan_array = $request->peserta_jabatan ?? [];
                $kontak_array = $request->peserta_kontak ?? [];
                $email_array = $request->peserta_email ?? [];

                $count = count($nama_array);
                for ($i = 0; $i < $count; $i++) {
                    $nama = trim($nama_array[$i] ?? '');
                    if (empty($nama)) continue;

                    $ttd_path = null;
                    if (isset($ttd_files[$i]) && $ttd_files[$i] instanceof \Illuminate\Http\UploadedFile) {
                        $ttd_path = Storage::disk('public')->putFile('ttd_peserta', $ttd_files[$i]);
                    }

                    $peserta_data_massal[] = [
                        'uid' => Str::uuid(),
                        'pengunjung_id' => $pengunjungUid,
                        'nama' => $nama,
                        'nip' => null,                                    // jika form tidak menyediakan NIP untuk peserta, simpan null
                        'jabatan' => $jabatan_array[$i] ?? null,
                        'email' => $email_array[$i] ?? null,
                        'wa' => $kontak_array[$i] ?? null,
                        'file_ttd' => $ttd_path,
                        'created_by' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // 6. Insert massal peserta
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
     * Mengunggah dan menyimpan file Surat Perintah Tugas (SPT)
     * ke dalam tabel kis_dokumen.
     */
    public function uploadSpt(Request $request, $uid)
    {
        // 1. Validasi File
        $request->validate([
            'file_spt' => 'required|file|mimes:pdf|max:2048', // Maks 2MB
        ], [
            'file_spt.required' => 'File SPT wajib diunggah.',
            'file_spt.mimes' => 'File harus berformat PDF.',
            'file_spt.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        $pengunjung = KisPengunjung::where('uid', $uid)->first();

        if (!$pengunjung) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            // 2. Simpan File ke Storage
            $file = $request->file('file_spt');
            // Menghasilkan nama file yang unik dan menyimpan ke folder 'dokumen_spt' di disk 'public'
            $path = $file->store('dokumen_spt', 'public'); 

            // 3. Cek apakah dokumen untuk pengunjung ini sudah ada di tabel KisDokumen
            // Jika ada, kita update. Jika belum ada, kita buat baru.
            $dokumen = KisDokumen::firstOrNew(['pengunjung_id' => $uid]);

            // Jika ini adalah record baru (INSERT):
            if (!$dokumen->exists) {
                // === BARIS PERBAIKAN UTAMA UNTUK ERROR 1364 ===
                $dokumen->uid = Str::uuid(); 
                // ===============================================
            } else {
                // Jika file SPT lama sudah ada, hapus file lama sebelum di-update
                if ($dokumen->file_spt) {
                    Storage::disk('public')->delete($dokumen->file_spt);
                }
            }
            
            // 4. Update path file dan simpan ke database
            $dokumen->file_spt = $path;
            $dokumen->save();

            DB::commit();

            return back()->with('success', 'Dokumen Surat Perintah Tugas (SPT) berhasil dilampirkan/diganti.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika terjadi error setelah file tersimpan, hapus file yang baru saja diupload
            if (isset($path)) {
                 Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Gagal mengunggah file SPT: ' . $e->getMessage());
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