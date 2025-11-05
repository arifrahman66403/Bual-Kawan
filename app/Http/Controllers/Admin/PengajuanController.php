<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use App\Models\KisLog; // Tambahkan import KisLog
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Tambahkan import Exception

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan kunjungan (Verifikasi Admin).
     */
    public function index()
    {
        // Ambil semua data pengunjung dengan pagination
        // Urutkan berdasarkan status 'pengajuan' (Menunggu) di atas, lalu tanggal kunjungan
        $pengunjungs = KisPengunjung::orderByRaw("FIELD(status, 'pengajuan', 'disetujui', 'ditolak', 'kunjungan', 'selesai')")
                                    ->orderBy('tgl_kunjungan', 'asc')
                                    ->paginate(10);

        // Pastikan Anda memiliki view ini: resources/views/admin/pengajuan/verifikasi.blade.php
        return view('admin.verify', compact('pengunjungs'));
    }

    /**
     * Memperbarui status pengajuan (disetujui/ditolak) dan mencatat tracking.
     * Route harus menggunakan POST ke /pengajuan/{uid}/status
     */
    public function updateStatus(Request $request, $uid)
    {
        // 1. Validasi Input Status
        $request->validate([
            // Tambahkan 'ditolak' sebagai status yang valid dari verifikasi admin
            'status' => 'required|in:disetujui,ditolak', 
        ]);
        
        $newStatus = $request->status;

        try {
            // 2. Cari data pengunjung berdasarkan UID
            $pengunjung = KisPengunjung::where('uid', $uid)->firstOrFail();

            // 3. Update Status Pengunjung
            $pengunjung->status = $newStatus;
            $pengunjung->save();

            // 4. Catat ke KisTracking
            KisTracking::create([
                'pengajuan_id' => $pengunjung->uid,
                'status' => $newStatus,
                'catatan' => $newStatus == 'disetujui' ? "Pengajuan disetujui oleh Admin." : "Pengajuan ditolak oleh Admin.",
                'created_by' => Auth::id() ?? 1 // Gunakan Auth::id() untuk konsistensi
            ]);
            
            // 5. === Logika QR Code (Hanya saat Disetujui) ===
            if ($newStatus === 'disetujui') {
                $qrString = 'SINGGAH-' . strtoupper(Str::random(10));
                $fileName = 'qr_' . $pengunjung->uid . '.png';
                $filePath = 'public/qr_codes/' . $fileName;

                // Buat QR Code dan simpan ke storage
                QrCode::format('png')
                    ->size(250)
                    ->errorCorrection('H')
                    ->generate($qrString, storage_path('app/' . $filePath));

                // Simpan metadata QR Code
                KisQrCode::updateOrCreate(
                    ['pengunjung_id' => $pengunjung->uid],
                    [
                        'qr_code' => 'storage/qr_codes/' . $fileName,
                        'berlaku_mulai' => Carbon::now(),
                        // Asumsi berlaku 1 hari sejak disetujui
                        'berlaku_sampai' => Carbon::now()->addDays(1), 
                        'created_by' => Auth::id() ?? 1,
                    ]
                );
            }

            // 6. === Catat Log Aktivitas ===
            KisLog::create([
                'user_id' => Auth::id() ?? 1,
                'pengunjung_id' => $pengunjung->uid,
                'aksi' => 'Mengubah status pengunjung (' . $pengunjung->nama_instansi . ') menjadi ' . $newStatus,
                'created_at' => now(),
            ]);

            // 7. Redirect dengan pesan sukses
            $message = ($newStatus == 'disetujui') 
                       ? '✅ Pengajuan berhasil **DISETUJUI** dan QR Code telah dibuat.' 
                       : '❌ Pengajuan berhasil **DITOLAK**.';

            return redirect()->route('admin.pengajuan')->with('success', $message);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            // Ini akan menangkap kegagalan lain, termasuk kegagalan QR Code/Database
            return redirect()->route('admin.pengajuan')->with('error', 'Terjadi kesalahan sistem saat memproses verifikasi: ' . $e->getMessage());
        }
    }
}