<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use App\Models\KisLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengunjungExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan kunjungan (Verifikasi Admin).
     */
    public function index()
    {
        $pengunjungs = KisPengunjung::orderByRaw("FIELD(status, 'pengajuan', 'disetujui', 'kunjungan', 'selesai')")
            ->orderBy('tgl_kunjungan', 'asc')
            ->paginate(10);

        return view('admin.pengajuan', compact('pengunjungs'));
    }

    /**
     * Mengekspor data riwayat tracking ke file Excel.
     */
    public function exportPengunjung()
    {
        $fileName = 'Daftar_Pengajuan_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // Panggil Export Class yang sudah kita buat
        return Excel::download(new PengunjungExport, $fileName);
    }

    /**
     * Memperbarui status pengajuan (disetujui/ditolak) dan mencatat tracking.
     */
    public function updateStatus(Request $request, $uid)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,selesai',
        ]);

        $newStatus = $request->status;

        try {
            $pengunjung = KisPengunjung::where('uid', $uid)->firstOrFail();

            // Update status
            $pengunjung->status = $newStatus;
            $pengunjung->save();

            // Jika status jadi selesai, expire / invalidasi QR
            if ($newStatus === 'selesai') {
                KisQrCode::where('pengunjung_id', $pengunjung->uid)
                    ->update(['berlaku_sampai' => now()]);
            }

            // Tracking
            KisTracking::create([
                'pengajuan_id' => $pengunjung->uid,
                'status' => $newStatus,
                'catatan' => $newStatus == 'disetujui'
                    ? "Pengajuan disetujui oleh Admin."
                    : "Pengajuan ditolak oleh Admin.",
                'created_by' => Auth::id() ?? 1,
            ]);

            // === BUAT QR CODE (pakai GD backend, TANPA imagick) === 
            if ($newStatus === 'disetujui') {
                
                // 1. Definisikan URL yang akan di-encode ke QR Code
                // URL ini akan mengarahkan ke halaman publik untuk input peserta
                // Asumsikan route 'pengunjung.scan' sudah didefinisikan (route('pengunjung.scan', $uid))
                $scanUrl = route('pengunjung.scan', $pengunjung->uid); // <-- PERBAIKAN PENTING
                
                $fileName = 'qr_' . $pengunjung->uid . '.png';
                $filePath = storage_path('app/public/qr_codes/' . $fileName);

                // ✅ Versi 6.x pakai constructor baru (tanpa setter)
                $qrCode = new QrCode(
                    // Ganti $qrString dengan $scanUrl
                    data: $scanUrl, // <-- Sekarang QR Code berisi URL publik
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 250,
                    margin: 10,
                    foregroundColor: new Color(0, 0, 0),
                    backgroundColor: new Color(255, 255, 255)
                );

                $writer = new PngWriter();
                $result = $writer->write($qrCode);

                // Simpan ke file
                // Pastikan folder 'storage/app/public/qr_codes' sudah ada
                $result->saveToFile($filePath);

                // Simpan metadata ke database
                KisQrCode::updateOrCreate(
                    ['pengunjung_id' => $pengunjung->uid],
                    [
                        // Menyimpan path file gambar QR
                        'qr_code' => 'storage/qr_codes/' . $fileName, 
                        // Opsional: Jika tabel KisQrCode memiliki kolom 'content'/'url',
                        // Anda bisa menyimpannya di sini untuk debugging: 'qr_content' => $scanUrl,
                        'berlaku_mulai' => now(),
                        'berlaku_sampai' => now()->addDay(),
                        'created_by' => Auth::id() ?? 1,
                    ]
                );
            }

            // Log Aktivitas
            KisLog::create([
                'user_id' => Auth::id() ?? 1,
                'pengunjung_id' => $pengunjung->uid,
                'aksi' => 'Mengubah status pengunjung (' . $pengunjung->nama_instansi . ') menjadi ' . $newStatus,
                'created_at' => now(),
            ]);

            $message = ($newStatus == 'disetujui')
                ? '✅ Pengajuan berhasil **DISETUJUI** dan QR Code telah dibuat.'
                : '❌ Pengajuan berhasil **DITOLAK**.';

            return redirect()->route('admin.pengajuan')->with('success', $message);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Terjadi kesalahan sistem saat memproses verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail pengajuan berdasarkan UID (lihat, dokumen, peserta, tracking, QR).
     */
    public function show($uid)
    {
        try {
            $pengunjung = KisPengunjung::where('uid', $uid)
                ->with(['qrCode', 'dokumen', 'peserta', 'tracking', 'createdBy'])
                ->firstOrFail();

            return view('admin.detail-pengajuan', compact('pengunjung'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
