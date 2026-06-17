<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class PayrollKehormatan extends Model
{
    protected $table = 'kehormatans';

    protected $guarded = ['id'];

    /**
     * Get all of the barokah_struktural for the PayrollKehormatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barokah_struktural()
    {
        return $this->hasMany(PayrollKehormatan::class, 't_kehormatan_id');
    }
}
