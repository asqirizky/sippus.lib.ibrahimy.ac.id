<?php

namespace App\Models\Barokah;

use App\Models\Master\Pustakawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarokahKhidmah extends Model
{
    // Mengunci nama tabel secara eksplisit
    protected $table = 'barokah_khidmah';

    // Mengizinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];

    /**
     * Relasi ke data master Pustakawan
     */
    public function pustakawan(): BelongsTo
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }
}
