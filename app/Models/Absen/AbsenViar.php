<?php

namespace App\Models\Absen;

use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use App\Models\Master\Ruang;
use Illuminate\Database\Eloquent\Model;

class AbsenViar extends Model
{
    protected $table = 'absen_viar';

    protected $guarded = ['id'];

    public function pustakawan ()
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }

    public function jadwal ()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
}
