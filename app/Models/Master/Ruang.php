<?php

namespace App\Models\Master;

use App\Models\Master\Libur;
use App\Models\Master\Pustakawan;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $table = 'ruangs';

    protected $guarded = ['id'];

    public function pustakawan()
    {
        return $this->hasMany(Pustakawan::class, 'ruang_id');
    }

    public function libur()
    {
        return $this->belongsTo(Libur::class);
    }

    public function setting()
    {
        return $this->hasMany(Setting::class, 'ruang_id', 'id');
    }

}
