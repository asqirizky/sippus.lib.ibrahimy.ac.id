<?php

namespace App\Models\Izin;

use App\Models\Master\Jadwal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IzinPustakawanJadwal extends Model
{
    protected $table = 'izin_pustakawan_jadwal';

    protected $guarded = ['id'];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }
}
