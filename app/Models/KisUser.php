<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class KisUser extends Authenticatable
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
        'is_active'
    ];

    protected $hidden = ['pass'];

    // method untuk cek role
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    // Override method untuk autentikasi
    public function getAuthPassword()
    {
        return $this->pass;
    }

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

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
