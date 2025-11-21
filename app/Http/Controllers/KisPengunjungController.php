<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisDokumen;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KisPengunjungController extends Controller
{
    public function index()
    {
        $pengunjungs = KisPengunjung::orderBy('created_at', 'desc')->get();
        return view('pengunjung.index', compact('pengunjungs'));
    }

    public function create()
    {
        return view('pengunjung.create');
    }

    public function storeKunjungan(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tgl_kunjungan' => 'required|date',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email',
            'wa_perwakilan' => 'required|string|max:20',
            'jabatan_perwakilan' => 'required|string|max:255',
        ]);

        $pengunjung = KisPengunjung::create([
            'uid' => Str::uuid(),
            'kode_daerah' => 'K-' . rand(1000, 9999),
            'nama_instansi' => $request->nama_instansi,
            'satuan_kerja' => $request->satuan_kerja,
            'tujuan' => $request->tujuan,
            'tgl_kunjungan' => $request->tgl_kunjungan,
            'nama_perwakilan' => $request->nama_perwakilan,
            'email_perwakilan' => $request->email_perwakilan,
            'wa_perwakilan' => $request->wa_perwakilan,
            'jabatan_perwakilan' => $request->jabatan_perwakilan,
            'status' => 'pengajuan',
            // 'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('kunjungan.index')
            ->with('success', 'Data pengunjung berhasil diajukan!');
    }

    /**
     * Menampilkan Form Pengajuan Kunjungan (Form Tambah Kunjungan).
     */
    public function showCreateForm()
    {
        return view('kunjungan.tambah_kunjungan');
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
            $path = $file->store('spt', 'public'); 

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
            return redirect()->route('kunjungan.detail')->with('error', 'Kunjungan tidak ditemukan atau URL tidak valid.');
        }
    }

    public function verifyList()
    {
        $pengunjungs = KisPengunjung::orderBy('created_at', 'desc')->get();
        return view('admin.verify', compact('pengunjungs'));
    }
}
