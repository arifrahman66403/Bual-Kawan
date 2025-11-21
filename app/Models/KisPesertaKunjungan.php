<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KisPesertaKunjungan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kis_peserta_kunjungan';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uid',
        'pengunjung_id',
        'nama',
        'nip',
        'jabatan',
        'email',
        'wa',
        'file_ttd',
        'created_by',
        'edited_by',
        'deleted_by',
    ];

    // Relasi
    public function pengunjung()
    {
        return $this->belongsTo(KisPengunjung::class, 'pengunjung_id', 'uid');
    }

    public function createdBy()
    {
        return $this->belongsTo(KisUser::class, 'created_by');
    }
}