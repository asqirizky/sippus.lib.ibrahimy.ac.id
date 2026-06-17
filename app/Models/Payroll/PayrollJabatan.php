<?php

namespace App\Models\Payroll;

use App\Models\Master\Jabatan;
use Illuminate\Database\Eloquent\Model;

class PayrollJabatan extends Model
{
    protected $table = 'tunjangan_jabatans';

    protected $guarded = ['id'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
    
