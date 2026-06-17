<?php

namespace App\Models\Master;

use App\Models\Master\Jadwal;
use App\Models\Master\Ruang;
use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    protected $table = 'liburs';
    protected $guarded = ['id'];

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'libur_shift', 'libur_id', 'jadwal_id');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

}
