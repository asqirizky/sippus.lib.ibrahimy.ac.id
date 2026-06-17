<?php

namespace App\Models\Barokah;

use App\Models\Master\Pustakawan;
use App\Models\Payroll\PayrollAnak;
use App\Models\Payroll\PayrollDosen;
use App\Models\Payroll\PayrollJabatan;
use App\Models\Payroll\PayrollKehadiran;
use App\Models\Payroll\PayrollKehormatan;
use App\Models\Payroll\PayrollPengabdian;
use App\Models\Payroll\PayrollTunkel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarokahStruktural extends Model
{
    protected $table = 'barokah_strukturals';

    protected $guarded = ['id'];

    /**
     * Get the pustakawan that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pustakawan(): BelongsTo
    {
        return $this->belongsTo(Pustakawan::class, 'pustakawan_id');
    }

    /**
     * Get the t_jabatan that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_jabatan(): BelongsTo
    {
        return $this->belongsTo(PayrollJabatan::class, 't_jabatan_id');
    }

    /**
     * Get the t_pengabdian that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_pengabdian(): BelongsTo
    {
        return $this->belongsTo(PayrollPengabdian::class, 't_pengabdian_id');
    }

    /**
     * Get the t_kehadiran that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_kehadiran(): BelongsTo
    {
        return $this->belongsTo(PayrollKehadiran::class, 't_kehadiran_id');
    }

    /**
     * Get the t_tunkel that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_tunkel(): BelongsTo
    {
        return $this->belongsTo(PayrollTunkel::class, 't_tunkel_id');
    }

    /**
     * Get the t_anak that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_anak(): BelongsTo
    {
        return $this->belongsTo(PayrollAnak::class, 't_anak_id');
    }

    /**
     * Get the t_kehormatan that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_kehormatan(): BelongsTo
    {
        return $this->belongsTo(PayrollKehormatan::class, 't_kehormatan_id');
    }

    /**
     * Get the t_dosen that owns the BarokahStruktural
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function t_dosen(): BelongsTo
    {
        return $this->belongsTo(PayrollDosen::class, 'rank_dosen_id');
    }
}
