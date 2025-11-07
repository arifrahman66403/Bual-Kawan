<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use App\Models\KisLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan kunjungan (Verifikasi Admin).
     */
    public function index()
    {
        $pengunjungs = KisPengunjung::orderByRaw("FIELD(status, 'pengajuan', 'disetujui', 'ditolak', 'kunjungan', 'selesai')")
            ->orderBy('tgl_kunjungan', 'asc')
            ->paginate(10);

        return view('admin.pengajuan', compact('pengunjungs'));
    }

    /**
     * Memperbarui status pengajuan (disetujui/ditolak) dan mencatat tracking.
     */
    public function updateStatus(Request $request, $uid)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $newStatus = $request->status;

        try {
            $pengunjung = KisPengunjung::where('uid', $uid)->firstOrFail();

            // Update status
            $pengunjung->status = $newStatus;
            $pengunjung->save();

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
                $qrString = 'SINGGAH-' . strtoupper(Str::random(10));
                $fileName = 'qr_' . $pengunjung->uid . '.png';
                $filePath = storage_path('app/public/qr_codes/' . $fileName);

                // === Generate QR Code (versi endroid/qr-code v5) ===
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrString)
                    ->encoding(new Encoding('UTF-8'))
                    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                    ->size(250)
                    ->margin(5)
                    ->backgroundColor(new Color(255, 255, 255))
                    ->foregroundColor(new Color(0, 0, 0))
                    ->build();

                // Simpan QR ke file
                $result->saveToFile($filePath);

                KisQrCode::updateOrCreate(
                    ['pengunjung_id' => $pengunjung->uid],
                    [
                        'qr_code' => 'storage/qr_codes/' . $fileName,
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
}
