<?php

namespace App\Models\Master;

use App\Models\Absen\AbsenKhidmah;
use App\Models\Absen\AbsenStruktural;
use App\Models\Barokah\BarokahStruktural;
use App\Models\Master\Ruang;
use Illuminate\Database\Eloquent\Model;

class Pustakawan extends Model
{
    protected $table = 'pustakawans';

    protected $guarded = ['id'];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Jadwal::class, 'pustakawan_jadwal', 'pustakawan_id', 'jadwal_id')->withPivot('hari');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function jadwal()
    {
        return $this->hasMany(PustakawanJadwal::class, 'pustakawan_id', 'id');
    }

    public function absen_struktural()
    {
        return $this->hasMany(AbsenStruktural::class, 'pustakawan_id');
    }

    public function absen_khidmah()
    {
        return $this->hasMany(AbsenKhidmah::class, 'pustakawan_id');
    }

    public function izin_struktural()
    {
        return $this->hasMany(Pustakawan::class, 'pustakawan_id');
    }

    public function barokah_struktural()
    {
        return $this->hasMany(BarokahStruktural::class, 'pustakawan_id');
    }

}
