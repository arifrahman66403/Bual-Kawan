<?php

namespace App\Exports;

use App\Models\KisTracking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // <-- BARU: Untuk lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithStyles;     // <-- BARU: Untuk styling (misal: bold)
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // <-- BARU: Import class untuk styles
use Carbon\Carbon;

// Tambahkan ShouldAutoSize dan WithStyles pada implements
class TrackingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles 
{
    /**
    * Mengambil data dari database
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Pastikan relasi 'pengunjung' dan 'createdBy' tetap dimuat
        return KisTracking::with(['pengunjung', 'createdBy'])
                        ->latest('created_at')
                        ->get();
    }

    /**
     * Menentukan header kolom di Excel
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', // Menggunakan ID standar (lebih pendek dari UID)
            'Nama Instansi',
            'Status Perubahan',
            'Catatan',
            'Diperbarui Oleh',
            'Tanggal Perubahan',
        ];
    }

    /**
     * Memetakan data dari koleksi ke baris Excel
     * @param mixed $tracking
     * @return array
     */
    public function map($tracking): array
    {
        // Ambil data dengan pemeriksaan null (??) yang lebih aman
        $namaInstansi = $tracking->pengunjung->nama_instansi ?? 'Tidak Ada Instansi';
        $updatedBy = $tracking->createdBy->nama ?? 'System';
        
        return [
            $tracking->id, // Gunakan ID standar (lebih mudah dibaca di Excel)
            $namaInstansi,
            ucfirst($tracking->status),
            $tracking->catatan ?? '-',
            $updatedBy,
            // Memastikan format tanggal rapi dan konsisten
            Carbon::parse($tracking->created_at)->format('d M Y H:i:s'), 
        ];
    }
    
    /**
     * Menambahkan styling pada header (Baru)
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Membuat baris pertama (Header) menjadi Bold dan ukuran font 12
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}