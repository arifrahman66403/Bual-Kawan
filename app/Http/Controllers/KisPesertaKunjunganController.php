<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisPengunjung;
use App\Models\KisDokumen;
use App\Models\KisPesertaKunjungan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KisPengunjungController extends Controller
{
    /**
     * Memproses dan menyimpan data pengajuan kunjungan ke 3 tabel.
     * (Dipindahkan dari GuestController)
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
        ]);

        DB::beginTransaction();
        $spt_path = null;
        $ttd_files = $request->file('peserta_ttd');

        try {
            // 2. Simpan Data KisPengunjung (Data Utama Kunjungan + Perwakilan)
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
                'jabatan' => $request->jabatan_perwakilan, 
                'nip' => $request->nip_perwakilan,
                'status' => 'pengajuan',
                'kode_qr' => 'QR-' . Str::random(8),
                'created_by' => null,
            ]);
            $pengunjungUid = $pengunjung->uid;

            // 3. Simpan File SPT (jika ada) ke KisDokumen
            if ($request->hasFile('file_spt')) {
                $spt_file = $request->file('file_spt');
                $spt_path = Storage::disk('public')->putFile('spt_pengajuan', $spt_file); 

                KisDokumen::create([
                    'uid' => Str::uuid(),
                    'pengunjung_id' => $pengunjungUid,
                    'file_spt' => $spt_path,
                    'created_by' => null,
                ]);
            }

            DB::commit();
            // Redirect ke halaman index di GuestController
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
     * Mengunggah dan menyimpan file Surat Perintah Tugas (SPT).
     */
    public function uploadSpt(Request $request, $uid)
    {
        // 1. Validasi File
        $request->validate([
            'file_spt' => 'required|file|mimes:pdf|max:2048',
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
            $path = $file->store('dokumen_spt_manajemen', 'public'); 

            // 3. Cek atau Buat Record Dokumen
            $dokumen = KisDokumen::firstOrNew(['pengunjung_id' => $uid]);

            if (!$dokumen->exists) {
                $dokumen->uid = Str::uuid(); 
            } else {
                // Hapus file lama jika ada sebelum di-update
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
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Gagal mengunggah file SPT: ' . $e->getMessage());
        }
    }
}
