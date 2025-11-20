<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KisQrCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kis_qr_code';

    protected $fillable = [
        'pengunjung_id',
        'qr_detail_path',
        'qr_scan_path',
        'berlaku_mulai',
        'berlaku_sampai',
        'created_by',
        'edited_by',
        'deleted_by',
    ];

    public function pengunjung()
    {
        return $this->belongsTo(KisPengunjung::class, 'pengunjung_id', 'uid');
    }

    public function createdBy()
    {
        return $this->belongsTo(KisUser::class, 'created_by');
    }
}
