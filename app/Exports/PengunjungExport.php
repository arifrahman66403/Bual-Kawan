<?php

namespace App\Exports;

use App\Models\KisPengunjung;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PengunjungExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * Mengambil data pengunjung dari database
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data pengunjung. Tambahkan orderBy jika perlu,
        // misalnya: return KisPengunjung::orderBy('created_at', 'desc')->get();
        return KisPengunjung::all();
    }

    /**
     * Menentukan header kolom di Excel
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Pengunjung',
            'Nama Instansi',
            'Satuan Kerja',
            'Nama Perwakilan',
            'Email Perwakilan',
            'No. WA Perwakilan',
            'Tujuan Kunjungan',
            'Tanggal Kunjungan',
            'Status',
            'Kode QR',
            'Tanggal Pengajuan',
        ];
    }

    /**
     * Memetakan data dari koleksi ke baris Excel
     * @param KisPengunjung $pengunjung
     * @return array
     */
    public function map($pengunjung): array
    {
        return [
            $pengunjung->uid,
            $pengunjung->nama_instansi,
            $pengunjung->satuan_kerja,
            $pengunjung->nama_perwakilan,
            $pengunjung->email_perwakilan,
            $pengunjung->wa_perwakilan,
            $pengunjung->tujuan,
            // Format tanggal kunjungan
            Carbon::parse($pengunjung->tgl_kunjungan)->format('d F Y'),
            ucfirst($pengunjung->status),
            $pengunjung->kode_qr,
            // Format tanggal pengajuan (created_at)
            Carbon::parse($pengunjung->created_at)->format('d M Y H:i:s'),
        ];
    }
    
    /**
     * Menambahkan styling pada header
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}