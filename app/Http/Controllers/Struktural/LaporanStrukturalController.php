<?php

namespace App\Http\Controllers\Struktural;

use App\Http\Controllers\Controller;
use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Libur;
use App\Models\Master\Pustakawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanStrukturalController extends Controller
{
    public function strukturalPDF(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

        $periode = Carbon::create($tahun, $bulan, 1)->format('Y-m');

        $jadwal = DB::table('pustakawan_jadwal')->get();

        $liburs = Libur::with('jadwals')->get();

        $barokahStruktural = DB::table('barokah_strukturals')->get();

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );


        $dates = [];
        foreach ($period as $dt) {
            $dates[] = $dt->format('Y-m-d');
        }

        $pustakawan = Pustakawan::with('jabatan')
            ->leftJoin('barokah_strukturals', function ($join) use ($periode) {
                $join->on('barokah_strukturals.pustakawan_id', '=', 'pustakawans.id'
                )->where('barokah_strukturals.periode', $periode);
            })
            ->leftJoin(
                'tunjangan_jabatans',
                'barokah_strukturals.t_jabatan_id',
                '=',
                'tunjangan_jabatans.id'
            )
            ->leftJoin(
                'tunjangan_pengabdians',
                'barokah_strukturals.t_pengabdian_id',
                '=',
                'tunjangan_pengabdians.id'
            )
            ->leftJoin(
                'tunjangan_kehadirans',
                'barokah_strukturals.t_kehadiran_id',
                '=',
                'tunjangan_kehadirans.id'
            )
            ->leftJoin(
                'tunkels',
                'barokah_strukturals.t_tunkel_id',
                '=',
                'tunkels.id'
            )
            ->leftJoin(
                'anaks',
                'barokah_strukturals.t_anak_id',
                '=',
                'anaks.id'
            )
            ->leftJoin(
                'barokah_rank_dosen',
                'barokah_strukturals.rank_dosen_id',
                '=',
                'barokah_rank_dosen.id'
            )
            ->leftJoin(
                'kehormatans',
                'barokah_strukturals.t_kehormatan_id',
                '=',
                'kehormatans.id'
            )
            ->join(
                'jabatans',
                'pustakawans.jabatan_id',
                '=',
                'jabatans.id'
            )->select('pustakawans.*',
                DB::raw('COALESCE(tunjangan_jabatans.tunjangan_jabatan, 0) as t_jabatan_id'),
                DB::raw('COALESCE(tunjangan_pengabdians.tunjangan_pengabdian, 0) as t_pengabdian_id'),
                DB::raw('COALESCE(tunjangan_kehadirans.tunjangan, 0) as t_kehadiran_id'),
                DB::raw('COALESCE(tunkels.tunkel, 0) as t_tunkel_id'),
                DB::raw('COALESCE(anaks.tunjangan_anak, 0) as t_anak_id'),
                DB::raw('COALESCE(barokah_rank_dosen.t_rank_dosen, 0) as rank_dosen_id'),
                DB::raw('COALESCE(kehormatans.tunjangan_kehormatan, 0) as t_kehormatan_id'),
                DB::raw('COALESCE(barokah_strukturals.sks, 0) as sks'),
            )->where('pustakawans.status', 1)
                ->where('jabatans.nama_jabatan', '!=', 'Tenaga Khidmah')
                ->where('pustakawans.tmt', '<=', $endDate)
                ->orderByRaw("CASE WHEN jabatans.nama_jabatan = 'Staff Magang' THEN 1 ELSE 0 END")
                ->orderBy('jabatans.eselon', 'asc')
                ->orderBy('pustakawans.nama_pustakawan', 'asc')
                ->get();



        $izinRaw = DB::table('izin_strukturals')
            ->join(
                'izin_struktural_jadwal',
                'izin_strukturals.id',
                '=',
                'izin_struktural_jadwal.izin_struktural_id'
            )
            ->join(
                'jadwals',
                'izin_struktural_jadwal.jadwal_id',
                '=',
                'jadwals.id'
            )
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

            $tanggal = Carbon::parse($row->tanggal)
                ->format('Y-m-d');

            $shift = strtolower(trim($row->shift));

            $ket = match (strtolower(trim($row->keterangan))) {
                'izin'            => 'I',
                'sakit'           => 'S',
                'tugas pesantren' => 'T',
                'libur'           => 'L',
                default           => null,
            };

            if ($ket) {
                $izinStruktural[$pustakawanId][$tanggal][$shift] = [
                    'ket' => $ket
                ];
            }
        }

        foreach ($pustakawan as $p) {

        $jadwalPustakawan = $jadwal->where('pustakawan_id', $p->id);

        $shiftPagi[$p->nama_pustakawan] = [];
        $shiftSiang[$p->nama_pustakawan] = [];
        $shiftMalam[$p->nama_pustakawan] = [];

            foreach ($dates as $tgl) {
                $hari = strtolower(Carbon::parse($tgl)->format('l'));
                $match = $jadwalPustakawan->firstWhere('hari', $hari);
                if ($match) {
                    if ($match->pagi == 1)  $shiftPagi[$p->nama_pustakawan][] = $tgl;
                    if ($match->siang == 1) $shiftSiang[$p->nama_pustakawan][] = $tgl;
                    if ($match->malam == 1) $shiftMalam[$p->nama_pustakawan][] = $tgl;
                }
            }
        }

        $jadwal = DB::table('pustakawan_jadwal')->get();

        $hadirPagi = $pustakawan->filter(fn($item) =>
            $jadwal->contains(fn($j) => $j->pustakawan_id == $item->id && $j->pagi == 1)
        );

        $hadirSiang = $pustakawan->filter(fn($item) =>
            $jadwal->contains(fn($j) => $j->pustakawan_id == $item->id && $j->siang == 1)
        );

        $hadirMalam = $pustakawan->filter(fn($item) =>
            $jadwal->contains(fn($j) => $j->pustakawan_id == $item->id && $j->malam == 1)
        );

        $absensi = DB::table('absen_strukturals')
            ->join('jadwals', 'absen_strukturals.jadwal_id', '=', 'jadwals.id')
            ->join('pustakawans', 'absen_strukturals.pustakawan_id', '=', 'pustakawans.id')
            ->whereBetween('absen_strukturals.tanggal', [$startDate, $endDate])
            ->select(
                'absen_strukturals.*',
                'jadwals.jadwal',
                'pustakawans.nama_pustakawan'
            )
            ->get()
            ->map(function ($x) {

                $shift = ucfirst(strtolower($x->jadwal));

                return (object)[
                    'pustakawan_id' => $x->pustakawan_id,
                    'nama_pustakawan' => $x->nama_pustakawan,
                    'tanggal' => Carbon::parse($x->tanggal)->format('Y-m-d'),
                    'shift' => $shift,
                    'jam_masuk' => $x->jam_masuk,
                    'keterangan' => $x->keterangan,
                ];
            })
            ->groupBy('shift')
            ->map(function ($shiftGroup) {
                return $shiftGroup->groupBy('pustakawan_id');
            });

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $jadwalGroup = $jadwal->groupBy('pustakawan_id');
        $data = AbsenStruktural::get();

        $pdf = Pdf::loadView('admin.Struktural.RekapStruktural.laporan_struktural', compact(
            'data',
            'bulan',
            'tahun',
            'liburs',
            'absensi',
            'hadirPagi',
            'hadirSiang',
            'hadirMalam',
            'namaBulan',
            'periode',
            'pustakawan',
            'startDate',
            'endDate',
            'izinStruktural',
            'jadwal',
            'dates',
            'jadwalGroup',
            'barokahStruktural',
            'tahun',
            ))->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-struktural.pdf');
    }
}
