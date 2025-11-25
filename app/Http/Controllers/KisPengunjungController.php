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
    /**
     * Menampilkan Daftar Kunjungan Aktif (Landing Page).
     */
    public function index(Request $request)
    {
        // 1. Ambil kata kunci pencarian
        $search = $request->input('search');

        // 2. Query dengan filter pencarian
        $kunjunganAktif = KisPengunjung::query()
            // Filter hanya jika ada input 'search'
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // Cari berdasarkan Nama Instansi
                    $q->where('nama_instansi', 'like', "%{$search}%")
                    // ATAU cari berdasarkan Satuan Kerja
                    ->orWhere('satuan_kerja', 'like', "%{$search}%")
                    // ATAU cari berdasarkan Tujuan (opsional)
                    ->orWhere('tujuan', 'like', "%{$search}%");
                });
            })
            // Tambahkan kondisi lain jika perlu (misal: status aktif)
            // ->where('status', 'aktif') 
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10) // Batasi 10 per halaman
            ->withQueryString(); // PENTING: Agar pencarian tidak hilang saat pindah halaman (page 2, dst)

        return view('kunjungan.kunjungan_aktif', [
            'title' => 'Daftar Kunjungan',
            'kunjunganAktif' => $kunjunganAktif
        ]);
    }

    public function create()
    {
        return view('pengunjung.create');
    }

    public function storeKunjungan(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tipe_pengunjung' => 'required|in:instansi pemerintah,masyarakat umum',
            'kode_daerah' => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tgl_kunjungan' => 'required|date',
            'file_kunjungan' => 'required_if:tipe_pengunjung,instansi pemerintah|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email',
            'wa_perwakilan' => 'required|string|max:20',
            'jabatan_perwakilan' => 'required|string|max:255',
            'file_spt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
        ]);

        // 2. Mulai Database Transaction
        // Semua query di dalamnya akan di-commit (disimpan) HANYA jika semua berhasil, 
        // jika ada yang gagal, semua akan di-rollback (dibatalkan).
        try {
            DB::beginTransaction();

            // A. 💾 SIMPAN DATA PENGUNJUNG (KIS_PENGUNJUNG)
            $pengunjung = KisPengunjung::create([
                'uid' => Str::uuid(),
                'tipe_pengunjung' => $request->tipe_pengunjung,
                'kode_daerah' => $request->kode_daerah,
                'nama_instansi' => $request->nama_instansi,
                'satuan_kerja' => $request->satuan_kerja,
                'tujuan' => $request->tujuan,
                'tgl_kunjungan' => $request->tgl_kunjungan,
                'file_kunjungan' => $request->hasFile('file_kunjungan') ? Storage::disk('public')->putFile('kunjungan', $request->file('file_kunjungan')) : null,
                'nama_perwakilan' => $request->nama_perwakilan,
                'email_perwakilan' => $request->email_perwakilan,
                'wa_perwakilan' => $request->wa_perwakilan,
                'jabatan_perwakilan' => $request->jabatan_perwakilan,
                'status' => 'pengajuan',
            ]);
            
            $sptPath = null;
            $catatanDokumen = 'Tidak ada SPT terlampir.';

            // C. 🖼️ TANGANI UPLOAD FILE DAN SIMPAN DATA DOKUMEN (KIS_DOKUMEN)
            if ($request->hasFile('file_spt')) {
                // 1. Simpan file ke Storage
                $filePath = $request->file('file_spt');
                $sptPath = Storage::disk('public')->putFile('spt', $filePath);

                // 2. Simpan path dan relasi di KisDokumen
                KisDokumen::create([
                    // Pastikan SEMUA kolom yang diperlukan ada di sini:
                    'uid' => Str::uuid(), // 🚨 HARUS DITAMBAHKAN
                    'pengunjung_id' => $pengunjung->uid, 
                    'file_spt' => $sptPath, 
                ]);
                // ...
            }

            // 3. Commit Transaction (Semua data disimpan permanen)
            DB::commit();

            return redirect()->route('kunjungan.index')
                ->with('success', 'Data pengunjung berhasil diajukan!');

        } catch (\Exception $e) {
            // 4. Rollback Transaction (Batalkan semua yang sudah terjadi di Try block)
            DB::rollBack();

            // Jika file sudah sempat ter-upload sebelum error terjadi, hapus file tersebut
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            
            // Log error (Opsional: tambahkan Log::error($e) untuk debug)
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengajukan kunjungan: ' . $e->getMessage());
        }
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
        // // 1. Tentukan peran yang diizinkan & Lakukan Pengecekan Otorisasi
        // // (Diasumsikan ini adalah versi aman yang Anda inginkan kembali)
        // $allowedRoles = ['admin', 'superadmin', 'operator'];
        
        // if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
        //     Log::warning('Akses Detail Kunjungan Ditolak: User ID ' . (Auth::id() ?? 'Guest') . ' mencoba mengakses UID: ' . $id);
        //     abort(403, 'Anda tidak memiliki izin untuk melihat detail kunjungan ini.');
        // }

        try {
            // 2. Ambil data kunjungan dengan relasi dokumen dan peserta.
            $pengunjung = KisPengunjung::where('uid', $id)
                ->with(['peserta', 'dokumen']) 
                ->firstOrFail();

            // 3. Siapkan Objek Data Perwakilan ($perwakilanPeserta)
            // Penggunaan (object) cast di sini sudah benar dan efisien untuk memformat data perwakilan.
            $perwakilanPeserta = (object) [
                'nama' => $pengunjung->nama_perwakilan,
                'email' => $pengunjung->email_perwakilan,
                'wa' => $pengunjung->wa_perwakilan,
                'jabatan' => $pengunjung->jabatan_perwakilan,
            ];


            // 4. Siapkan Koleksi Anggota Rombongan ($anggotaRombongan)
            $anggotaRombongan = $pengunjung->peserta; 

            // 5. Kirim data yang dibutuhkan ke View
            return view('kunjungan.detail', [
                'perwakilanPeserta' => $perwakilanPeserta,
                'anggotaRombongan' => $anggotaRombongan,
                'pengunjung' => $pengunjung,
                'title' => 'Detail Kunjungan',
            ]);

        } catch (ModelNotFoundException $e) {
            // Jika data tidak ditemukan, redirect ke route index atau daftar kunjungan, 
            // bukan ke 'kunjungan.detail' lagi (karena itu akan mengulang error).
            // Saya asumsikan route index adalah 'admin.kunjungan.index'.
            return redirect()->route('admin.kunjungan.index')->with('error', 'Kunjungan tidak ditemukan atau URL tidak valid.');
        }
    }

    public function verifyList()
    {
        $pengunjungs = KisPengunjung::orderBy('created_at', 'desc')->get();
        return view('admin.verify', compact('pengunjungs'));
    }
}
