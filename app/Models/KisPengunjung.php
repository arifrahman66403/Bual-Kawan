<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class KisPengunjung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kis_pengunjung';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'uid',
        'kode_daerah',
        'nama_instansi',
        'satuan_kerja',
        'tujuan',
        'tgl_kunjungan',
        'nama_perwakilan',
        'email_perwakilan',
        'wa_perwakilan',
        'jabatan_perwakilan',
        'status',
        'created_by',
        'edited_by',
        'deleted_by',
    ];

    // Tambahkan 'qr_detail_url' ke $appends agar otomatis tersedia jika di-JSON-kan
    protected $appends = ['qr_detail_url'];

    // Relasi
    public function peserta()
    {
        // Menghubungkan kis_pengunjung.uid -> kis_peserta_kunjungan.pengunjung_id
        return $this->hasMany(KisPesertaKunjungan::class, 'pengunjung_id', 'uid');
    }

    public function getRouteKeyName()
    {
        return 'uid';
    }

    public function dokumen()
    {
        return $this->hasOne(KisDokumen::class, 'pengunjung_id', 'uid');
    }

    public function qrCode()
    {
        return $this->hasOne(KisQrCode::class, 'pengunjung_id', 'uid');
    }

    // Accessor untuk mendapatkan URL QR Code Detail
    public function getQrDetailUrlAttribute()
    {
        $qrCode = $this->qrCode; 
        
        if ($qrCode && $qrCode->qr_detail_path) {
            // Menggunakan Storage::url() yang lebih baik daripada asset('storage/...')
            return Storage::url($qrCode->qr_detail_path); 
        }
        
        return null;
    }

    public function tracking()
    {
        return $this->hasMany(KisTracking::class, 'pengajuan_id', 'uid');
    }

    public function log()
    {
        return $this->hasMany(KisLog::class, 'pengunjung_id', 'uid');
    }

    public function createdBy()
    {
        return $this->belongsTo(KisUser::class, 'created_by');
    }
}