<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // Relasi
    public function peserta()
    {
        return $this->hasMany(KisPesertaKunjungan::class, 'pengunjung_id', 'uid', 'is_perwakilan');
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

    public function qrCodeDetail()
    {
        return $this->hasOne(KisQrCode::class, 'pengunjung_id', 'uid')
            ->where('qr_detail_path', 'detail')
            ->withTrashed();
    }

    // Jika QR Code path disimpan di relasi KisQrCode
    public function getQrDetailUrlAttribute()
    {
        // Asumsikan relasi qrCode() ada dan kolom baru di KisQrCode adalah 'qr_detail_path'
        $qrCode = $this->qrCode; 
        
        if ($qrCode && $qrCode->qr_detail_path) {
            // Asumsikan file disimpan di storage/app/public/qr_code/
            return asset('storage/' . $qrCode->qr_detail_path); 
        }
        
        return null;
    }

    // Tambahkan 'qr_detail_url' ke $appends jika menggunakan JSON
    protected $appends = ['qr_detail_url'];

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
