<?php

namespace App\Http\Controllers;

use App\Models\Absen\AbsenKhidmah;
use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Jadwal;
use App\Models\Master\Libur;
use App\Models\Master\Pustakawan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $liburs = Libur::with('jadwals')->get();

        $izinRaw = DB::table('izin_strukturals')
            ->join('izin_struktural_jadwal', 'izin_strukturals.id', '=', 'izin_struktural_jadwal.izin_struktural_id')
            ->join('jadwals', 'izin_struktural_jadwal.jadwal_id', '=', 'jadwals.id')
            ->whereBetween('izin_struktural_jadwal.tanggal', [$startDate, $endDate])
            ->select(
                'izin_strukturals.pustakawan_id',
                'izin_strukturals.keterangan',
                'izin_struktural_jadwal.tanggal',
                'jadwals.jadwal as shift'
            )
            ->get();

        $izinStruktural = [];

        foreach ($izinRaw as $row) {
            $pustakawanId = $row->pustakawan_id;
            $tanggal = Carbon::parse($row->tanggal)->format('Y-m-d');
            $shift = strtolower(trim($row->shift));

            $ket = match (strtolower(trim($row->keterangan))) {
                'izin' => 'I',
                'sakit' => 'S',
                'tugas pesantren' => 'T',
                'libur' => 'L',
                default => null,
            };

            if ($ket) {
                $izinStruktural[$pustakawanId][$tanggal][$shift] = [
                    'ket' => $ket
                ];
            }
        }

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

            $totalShiftEfektif = 0;

            $totalI = 0;
            $totalS = 0;
            $totalT = 0;
            $totalA = 0;
            $totalL = 0;

            foreach ($dates as $tanggal) {
                $hari = Carbon::parse($tanggal)->translatedFormat('l');
                $jadwalHari = $item->jadwal->firstWhere('hari', $hari);

                if (!$jadwalHari) {
                    continue;
                }

                foreach (['pagi', 'siang', 'malam'] as $shift) {
                    if ($jadwalHari->$shift != 1) {
                        continue;
                    }

                    if ($shift == 'pagi' && $hari != 'Jumat') {
                        continue;
                    }

                    if ($shift == 'siang' && $hari == 'Jumat') {
                        continue;
                    }

                    if ($shift == 'malam' && $hari == 'Kamis') {
                        continue;
                    }

                    $totalShiftEfektif++;

                    $isLibur = false;
                    foreach ($liburs as $liburItem) {
                        if ($liburItem->tanggal != $tanggal) {
                            continue;
                        }

                        if (!is_null($liburItem->ruang_id) && $liburItem->ruang_id != $item->ruang_id) {
                            continue;
                        }

                        foreach ($liburItem->jadwals as $jadwalLibur) {
                            if (strtolower(trim($jadwalLibur->jadwal)) == $shift) {
                                $isLibur = true;
                                break 2;
                            }
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

                    $izinShift = $izinStruktural[$item->id][$tanggal][$shift]['ket'] ?? null;

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

            $totalPotongan = $TI + $TS + $totalT + $totalA;

            $persentase = $totalShiftEfektif > 0
                ? round((($totalShiftEfektif - $totalPotongan) / $totalShiftEfektif) * 100)
                : 0;

            $item->setAttribute('persentase', $persentase);
        }

        return view('admin.home', compact('pustakawan'));
    }
}
