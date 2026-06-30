<?php

namespace App\Http\Controllers;

use App\Models\Absen\AbsenKhidmah;
use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $bulan = now()->month;
    $tahun = now()->year;

    $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
    $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();

    $dates = [];
    $period = CarbonPeriod::create($startDate, $endDate);

    foreach ($period as $date) {
        $dates[] = $date->format('Y-m-d');
    }

    $totalHari = count($dates);

    $pustakawan = Pustakawan::with(['jabatan', 'jadwal'])
        ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
        ->where('pustakawans.status', 1)
        ->orderBy('jabatans.eselon', 'asc')
        ->orderBy('pustakawans.nama_pustakawan', 'asc')
        ->select('pustakawans.*')
        ->get();

    foreach ($pustakawan as $item) {

    $isKhidmah = $item->jabatan && strtolower($item->jabatan->nama_jabatan) === 'tenaga khidmah';

    $queryAbsen = $isKhidmah ? AbsenKhidmah::class : AbsenStruktural::class;

    $jumlahHadir = $queryAbsen::where('pustakawan_id', $item->id)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->count();

    $totalShiftEfektif = 0;

    $totalI = 0;
    $totalS = 0;
    $totalT = 0;
    $totalA = 0;
    $totalL = 0;

    foreach ($dates as $tanggal) {

        // gunakan bahasa indonesia sesuai database
        $hari = Carbon::parse($tanggal)
            ->locale('id')
            ->translatedFormat('l');

        // ambil jadwal berdasarkan hari
        $jadwalHari = $item->jadwal
            ->firstWhere('hari', $hari);

        if (!$jadwalHari) {
            continue;
        }

        foreach (['pagi', 'siang', 'malam'] as $shift) {

            // cek shift aktif
            if ($jadwalHari->$shift != 1) {
                continue;
            }

            $totalShiftEfektif++;

            $isLibur = false;

            foreach ($liburByDateShift[$tanggal] ?? [] as $l) {

                if ($l['ruang_id'] === 'Semua Ruang') {

                    $isLibur = true;
                    break;
                }
            }

            if ($isLibur) {
                continue;
            }

            $hadir = $queryAbsen::where('pustakawan_id', $item->id)
                ->whereDate('tanggal', $tanggal)
                ->whereHas('jadwal', function ($q) use ($shift) {
                    $q->whereRaw('LOWER(jadwal) = ?', [$shift]);
                })
                ->exists();

            $izinShift =
                $izin[$item->id][$tanggal][$shift]['ket']
                ?? null;

            if ($hadir) {
                continue;
            }

            if ($izinShift) {

                if ($izinShift == 'I') {
                    $totalI++;
                } elseif ($izinShift == 'S') {
                    $totalS++;
                } elseif ($izinShift == 'T') {
                    $totalT++;
                } elseif ($izinShift == 'L') {
                    $totalL++;
                }

            } else {

                $totalA++;
            }
        }
    }

    $TI = $totalI / 2;
    $TS = $totalS / 2;

    $totalPotongan =
        $TI +
        $TS +
        $totalT +
        $totalA;

    $persentase = $totalShiftEfektif > 0
        ? round(
            (
                ($totalShiftEfektif - $totalPotongan)
                / $totalShiftEfektif
            ) * 100
        )
        : 0;

    $item->setAttribute('persentase', $persentase);
}

    return view('admin.home',compact('pustakawan'));
}

}
