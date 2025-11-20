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

    public function qrCodeDetail()
    {
        return $this->hasOne(KisQrCode::class, 'pengunjung_id', 'uid')
            ->where('qr_type', 'detail')
            ->withTrashed();
    }

    public function getQrCodeUrlAttribute()
    {
        // Asumsikan relasi qrCodeDetail sudah ada dan kolom qr_code berisi 'qr_codes/qr_detail_UID.png'
        if ($this->qrCodeDetail && $this->qrCodeDetail->qr_code) {
            // Ini akan menghasilkan URL seperti: http://domainanda.com/storage/qr_codes/qr_detail_UID.png
            return asset('storage/' . $this->qrCodeDetail->qr_code); 
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
