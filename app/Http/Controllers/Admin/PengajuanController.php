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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'status' => 'required|in:pengajuan,disetujui,ditolak,selesai'
        ]);

        try {
            $pengunjung = KisPengunjung::where('uid', $uid)->firstOrFail();
            $newStatus = $request->status;

            // update status pengunjung
            $pengunjung->status = $newStatus;
            $pengunjung->save();

            // create tracking entry
            KisTracking::create([
                'pengajuan_id' => $pengunjung->uid,
                'catatan' => match($newStatus) {
                    'disetujui' => 'Pengajuan disetujui oleh admin.',
                    'ditolak' => 'Pengajuan ditolak oleh admin.',
                    'selesai' => 'Pengajuan ditandai selesai oleh admin.',
                    default => 'Status diperbarui oleh admin.',
                },
                'status' => $newStatus,
                'created_by' => Auth::id(),
            ]);

            // Jika disetujui -> generate/aktifkan QR Code (jika belum ada)
            if ($newStatus === 'disetujui') {
                $existing = KisQrCode::where('pengunjung_id', $pengunjung->uid)->first();
                if (! $existing) {
                    // generate QR image (payload = link ke form peserta)
                    $payload = url("pengunjung/scan/{$pengunjung->uid}"); // sesuaikan route target
                    $qr = QrCode::create($payload)
                        ->setEncoding(new Encoding('UTF-8'))
                        ->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
                    $writer = new PngWriter();
                    $result = $writer->write($qr);

                    $filename = 'qrcodes/' . $pengunjung->uid . '.png';
                    Storage::disk('public')->put($filename, $result->getString());
                    $publicPath = 'storage/' . $filename;

                    KisQrCode::create([
                        'uid' => (string) Str::uuid(),
                        'pengunjung_id' => $pengunjung->uid,
                        'qr_code' => $publicPath,
                        'berlaku_mulai' => now(),
                        'berlaku_sampai' => null,
                    ]);
                } else {
                    // jika sudah ada, aktifkan/refresh berlaku_mulai
                    $existing->berlaku_mulai = now();
                    $existing->berlaku_sampai = null;
                    $existing->save();
                }
            }

            // Jika selesai -> expire QR
            if ($newStatus === 'selesai') {
                KisQrCode::where('pengunjung_id', $pengunjung->uid)
                    ->update(['berlaku_sampai' => now()]);
            }

            return redirect()->route('admin.pengajuan')->with('success', 'Status pengajuan berhasil diubah.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
