<?php

namespace App\Models\Struktural;

use App\Models\Master\Jadwal;
use Illuminate\Database\Eloquent\Model;

class IzinStrukturalJadwal extends Model
{
    protected $table = 'izin_struktural_jadwal';

    protected $guarded = ['id'];

    public function izin()
    {
        return $this->belongsTo(IzinStruktural::class, 'izin_struktural_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
