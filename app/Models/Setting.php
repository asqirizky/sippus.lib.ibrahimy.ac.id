<?php

namespace App\Models;

use App\Models\Master\Ruang;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'koordinat_setting';

    protected $guarded = ['id'];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id', 'id');
    }

}
