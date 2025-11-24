<?php

namespace App\Exports;

use App\Models\KisTracking;
use App\Models\KisPengunjung;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TrackingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $tipe;

    // Constructor untuk menerima filter
    public function __construct($bulan = null, $tahun = null, $tipe = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->tipe = $tipe; // Ambil filter tipe pengunjung dari request
    }

    /**
    * Mengambil data dari database dengan filter
    */
    public function collection()
    {
        $query = KisTracking::with(['pengunjung', 'createdBy'])
                        ->latest('created_at');

        // Filter berdasarkan Bulan (jika ada)
        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        // Filter berdasarkan Tahun (jika ada)
        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun);
        }

        // Filter Tipe Pengunjung
        if ($this->tipe) {
            $query->whereHas('pengunjung', function ($q) {
                $q->where('tipe_pengunjung', $this->tipe);
            });
        }

        return $query->get();
    }

    // ... (Method headings, map, styles TETAP SAMA seperti sebelumnya) ...
    
    public function headings(): array
    {
        return [
            'ID',
            'Nama Instansi', 
            'Status Perubahan', 
            'Catatan', 
            'Diperbarui Oleh', 
            'Tanggal Perubahan',
        ];
    }

    public function map($tracking): array
    {
        $namaInstansi = $tracking->pengunjung->nama_instansi ?? 'Tidak Ada Instansi';
        $updatedBy = $tracking->createdBy->nama ?? 'System';
        
        return [
            $tracking->id,
            $namaInstansi,
            ucfirst($tracking->status),
            $tracking->catatan ?? '-',
            $updatedBy,
            Carbon::parse($tracking->created_at)->format('d M Y H:i:s'), 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}