<?php

namespace App\Models\Izin;

use App\Models\Master\Pustakawan;
use Illuminate\Database\Eloquent\Model;


class IzinPustakawan extends Model
{
    //
    // Kunci ke nama tabel di phpMyAdmin tadi
    protected $table = 'izin_pustakawans';

    protected $guarded = ['id'];

    // Relasi balik ke Pustakawan
    public function pustakawan()
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }

    // Relasi ke tabel detail jadwal hasil looping
    public function jadwals()
    {
        return $this->hasMany(IzinPustakawanJadwal::class, 'izin_pustakawan_id');
    }
}
    