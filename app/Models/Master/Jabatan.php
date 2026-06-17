<?php

namespace App\Models\Master;

use App\Models\Kehadiran\Pegawai;
use App\Models\Payroll\PayrollJabatan;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatans';

    protected $guarded = ['id'];

    public function tunjangan ()
    {
        return $this->hasMany(PayrollJabatan::class, 'jabatan_id',);
    }

    public function pustakawan()
    {
        return $this->hasMany(Pustakawan::class, 'jabatan_id');
    }

    public function absen_struktural() 
    {
        return $this->hasMany(AbsenStruktural::class, 'jadwal_id');
    }
    
}
