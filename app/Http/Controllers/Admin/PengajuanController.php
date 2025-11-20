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

            // === LOGIKA GABUNGAN PEMBUATAN QR CODE === 
            if ($newStatus === 'disetujui') {
                
                // 1. QR CODE DETAIL KUNJUNGAN (Untuk link publik)
                $detailUrl = route('kunjungan.detail', $pengunjung->uid); 
                $fileNameDetail = 'qr_detail_' . $pengunjung->uid . '.png';
                $filePathDetail = storage_path('app/public/qr_code/' . $fileNameDetail); 
                
                $qrCodeDetail = new QrCode(
                    data: $detailUrl, encoding: new Encoding('UTF-8'), errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 250, margin: 10, foregroundColor: new Color(0, 0, 0), backgroundColor: new Color(255, 255, 255)
                );
                (new PngWriter())->write($qrCodeDetail)->saveToFile($filePathDetail);
                
                // 2. QR CODE SCAN PESERTA (Untuk link input/scan)
                $scanUrl = route('pengunjung.scan', $pengunjung->uid); 
                $fileNameScan = 'qr_scan_' . $pengunjung->uid . '.png';
                $filePathScan = storage_path('app/public/qr_code/' . $fileNameScan);

                $qrCodeScan = new QrCode(
                    data: $scanUrl, encoding: new Encoding('UTF-8'), errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 250, margin: 10, foregroundColor: new Color(0, 0, 0), backgroundColor: new Color(255, 255, 255)
                );
                (new PngWriter())->write($qrCodeScan)->saveToFile($filePathScan);

                // 3. Simpan Metadata GABUNGAN ke KisQrCode
                KisQrCode::updateOrCreate(
                    ['pengunjung_id' => $pengunjung->uid],
                    [
                        'qr_detail_path' => 'qr_code/' . $fileNameDetail, // Kolom BARU
                        'qr_scan_path'   => 'qr_code/' . $fileNameScan,   // Kolom BARU
                        'berlaku_mulai'  => now(),
                        'berlaku_sampai' => now()->addDay(),
                        'created_by'     => Auth::id() ?? 1,
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
                : '✅ Kunjungan telah **SELESAI**.';

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
            // TIDAK ADA FILTER is_perwakilan pada Model KisPengunjung di sini.
            $pengunjung = KisPengunjung::where('uid', $uid)
                ->with([
                    // 1. FILTER RELASI: Filter hanya diterapkan pada KisPesertaKunjungan (relasi 'peserta')
                    'peserta' => function ($query) {
                        $query->where('is_perwakilan', 0); // HANYA memuat peserta non-perwakilan
                    },
                    // 2. Load relasi lainnya
                    'qrCode', 
                    'dokumen', 
                    'tracking', 
                    'createdBy'
                ])
                ->firstOrFail();

            return view('admin.detail-pengajuan', compact('pengunjung'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Data pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
