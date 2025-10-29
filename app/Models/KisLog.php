<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KisLog extends Model
{
    use HasFactory;

    protected $table = 'kis_log';

    protected $fillable = [
        'user_id',
        'pengunjung_id',
        'aksi',
    ];

    public function user()
    {
        return $this->belongsTo(KisUser::class, 'user_id');
    }

    public function pengunjung()
    {
        return $this->belongsTo(KisPengunjung::class, 'pengunjung_id', 'uid');
    }
}
