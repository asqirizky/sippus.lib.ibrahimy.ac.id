<?php

namespace App\Models\Struktural;

use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use App\Models\Struktural\IzinStrukturalJadwal;
use Illuminate\Database\Eloquent\Model;

class IzinStruktural extends Model
{
    protected $table = 'izin_strukturals';

    protected $guarded = ['id'];

    public function jadwals()
    {
        return $this->hasMany(IzinStrukturalJadwal::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }


    public function pustakawan()
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }

}
