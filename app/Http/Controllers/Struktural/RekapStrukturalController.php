<?php

namespace App\Http\Controllers\Struktural;

use App\Http\Controllers\Controller;
use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Pustakawan;
use App\Models\Struktural\IzinStruktural;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapStrukturalController extends Controller
{
    public function index (Request $request)
    {
        $tanggal = Carbon::parse($request->input('tanggal', Carbon::now()))->format('Y-m-d');

        $absenStruktural = AbsenStruktural::whereDate('tanggal', $tanggal)->get();
        $izin = IzinStruktural::whereDate('tanggal_mulai', '<=', $tanggal)
                                ->whereDate('tanggal_selesai', '>=', $tanggal)
                                ->get();

        $totalStruktural = Pustakawan::whereHas('jabatan', function ($q) {
            $q->where('nama_jabatan', '!=', 'Tenaga Khidmah');
        })->count();
        $hadir = $absenStruktural->count();
        $izinJumlah = $izin->count();
        $tanpaKeterangan = $totalStruktural - ($hadir + $izinJumlah);

        $struktural = AbsenStruktural::whereDate('tanggal', $tanggal)->get();

        return view('admin.Struktural.RekapStruktural.rekap_struktural', compact(
            'struktural',
            'tanpaKeterangan',
            'tanggal',
            'izin',
            'izinJumlah',
            'hadir',
        ));
    }
}
