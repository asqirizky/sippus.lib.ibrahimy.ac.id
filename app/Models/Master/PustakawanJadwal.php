<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class PustakawanJadwal extends Model
{
    protected $table = 'pustakawan_jadwal';

    protected $guarded = ['id'];

    public function pustakawan () 
    {
        return $this->belongsTo(Pustakawan::class, 'psutakawan_id');
    }
}
