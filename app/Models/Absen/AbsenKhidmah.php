<?php

namespace App\Models\Absen;

use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use Illuminate\Database\Eloquent\Model;

class AbsenKhidmah extends Model
{
    protected $table = 'absen_khidmah';

    protected $guarded = ['id'];

    public function pustakawan ()
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }

    public function jadwal ()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

}
