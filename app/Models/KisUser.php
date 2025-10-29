<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KisUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kis_user';
    protected $fillable = [
        'nama',
        'email',
        'user',
        'wa',
        'role',
        'pass',
    ];

    protected $hidden = ['pass'];

    // Relasi
    public function pengunjungDibuat()
    {
        return $this->hasMany(KisPengunjung::class, 'created_by');
    }

    public function pesertaDibuat()
    {
        return $this->hasMany(KisPesertaKunjungan::class, 'created_by');
    }

    public function dokumenDibuat()
    {
        return $this->hasMany(KisDokumen::class, 'created_by');
    }

    public function qrCodeDibuat()
    {
        return $this->hasMany(KisQrCode::class, 'created_by');
    }

    public function trackingDibuat()
    {
        return $this->hasMany(KisTracking::class, 'created_by');
    }

    public function log()
    {
        return $this->hasMany(KisLog::class, 'user_id');
    }
}
