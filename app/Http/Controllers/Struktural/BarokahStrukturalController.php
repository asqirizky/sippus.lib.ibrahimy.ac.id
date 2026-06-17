<?php

namespace App\Http\Controllers\Struktural;

use App\Http\Controllers\Controller;
use App\Models\Barokah\BarokahStruktural;
use App\Models\Master\Pustakawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarokahStrukturalController extends Controller
{

    public function index(Request $request) {

        $now = Carbon::now();

        $bulan = $request->bulan ?? $now->month;
        $tahun = $request->tahun ?? $now->year;

        $periode = sprintf('%04d-%02d', $tahun, $bulan);

        $barokah = BarokahStruktural::with('pustakawan')
            ->where('periode', $periode)
            ->get();


        return view('admin.Struktural.BarokahStruktural.barokah_pustakawan', compact(
            'barokah',
            'periode',
            'bulan',
            'tahun',
        ));
    }

    public function generate(Request $request)
    {
        $now = Carbon::now();

        $bulan = $request->bulan ?? $now->month;
        $tahun = $request->tahun ?? $now->year;
        $apbm  = $request->apbm ?? $tahun;

        $periode = Carbon::create($tahun, $bulan, 1)->format('Y-m');

        $tanggalMulai = Carbon::create($tahun, $bulan, 1)
            ->startOfMonth()
            ->format('Y-m-d');

        $tanggalSelesai = Carbon::create($tahun, $bulan, 1)
            ->endOfMonth()
            ->format('Y-m-d');

        $pengabdianRow = DB::table('tunjangan_pengabdians')
            ->where('APBM', $apbm)
            ->first();

        $tunkelRow = DB::table('tunkels')
            ->where('APBM', $apbm)
            ->first();

        $kehormatanRow = DB::table('kehormatans')
            ->where('APBM', $apbm)
            ->first();

        $anakRow = DB::table('anaks')
            ->where('APBM', $apbm)
            ->first();

        $pustakawans = Pustakawan::query()
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')

            ->where('pustakawans.status', 1)
            ->where('jabatans.nama_jabatan', '!=', 'Tenaga Khidmah')

            ->leftJoin('tunjangan_kehadirans as tk', function ($join) use ($apbm) {
                $join->on('tk.tempatTinggal', '=', 'pustakawans.domisili')
                    ->where('tk.APBM', $apbm);
            })

            ->leftJoin('tunjangan_jabatans as tj', function ($join) use ($apbm) {
                $join->on('tj.jabatan_id', '=', 'pustakawans.jabatan_id')
                    ->where('tj.APBM', $apbm);
            })

            ->leftJoin('barokah_rank_dosen as brd', function ($join) use ($apbm) {

                $join->on('brd.pendidikan_terakhir', '=', 'pustakawans.pend_terakhir')

                    ->where('brd.APBM', $apbm)

                    ->whereRaw("
                        brd.tahun = (
                            SELECT MAX(tahun)
                            FROM barokah_rank_dosen
                            WHERE pendidikan_terakhir = pustakawans.pend_terakhir
                            AND APBM = ?
                            AND tahun <= (
                                YEAR(CURDATE()) - YEAR(pustakawans.tmt_mengajar) + 1
                            )
                        )
                    ", [$apbm]);
            })

            ->select([

                'pustakawans.id',
                'pustakawans.nama_pustakawan',
                'pustakawans.domisili',
                'pustakawans.jabatan_id',
                'pustakawans.tmt_mengajar',
                'pustakawans.pend_terakhir',

                /*
                |--------------------------------------------------------------------------
                | ID ASLI UNTUK DISIMPAN
                |--------------------------------------------------------------------------
                */

                DB::raw('COALESCE(tj.id,0) as jabatan_id'),
                DB::raw('COALESCE(tk.id,0) as kehadiran_id'),
                DB::raw(($pengabdianRow->id ?? 0) . ' as pengabdian_id'),
                DB::raw(($tunkelRow->id ?? 0) . ' as tunkel_id'),
                DB::raw(($kehormatanRow->id ?? 0) . ' as kehormatan_id'),
                DB::raw(($anakRow->id ?? 0) . ' as anak_id'),

                /*
                |--------------------------------------------------------------------------
                | NOMINAL
                |--------------------------------------------------------------------------
                */

                DB::raw('COALESCE(tj.tunjangan_jabatan,0) as jabatan'),
                DB::raw('COALESCE(tk.tunjangan,0) as kehadiran'),

                DB::raw(($pengabdianRow->tunjangan_pengabdian ?? 0) . ' as pengabdian'),
                DB::raw(($tunkelRow->tunkel ?? 0) . ' as tunkel'),
                DB::raw(($kehormatanRow->tunjangan_kehormatan ?? 0) . ' as kehormatan'),
                DB::raw(($anakRow->tunjangan_anak ?? 0) . ' as anak'),

                /*
                |--------------------------------------------------------------------------
                | RANK DOSEN
                |--------------------------------------------------------------------------
                */

                DB::raw('COALESCE(brd.id,0) as rank_dosen_id'),
                DB::raw('COALESCE(brd.t_rank_dosen,0) as t_rank_dosen'),

            ])

            ->orderBy('jabatans.eselon')
            ->orderBy('pustakawans.nama_pustakawan')
            ->get();

        $barokah = BarokahStruktural::where('periode', $periode)
            ->get()
            ->keyBy('pustakawan_id');

        return view(
            'admin.Struktural.BarokahStruktural.generate',
            compact(
                'pustakawans',
                'barokah',
                'periode',
                'bulan',
                'tahun',
                'apbm',
                'tanggalMulai',
                'tanggalSelesai'
            )
        );
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $pustakawanData = $request->pustakawan ?? [];

            $sksData = $request->sks_dosen ?? [];

            $apbm = $request->apbm ?? date('Y');

            $periode = isset($request->periode)
                ? Carbon::createFromFormat('Y-m', $request->periode)->format('Y-m')
                : now()->format('Y-m');

            foreach ($pustakawanData as $pustakawanId => $data) {

                $pustakawan = Pustakawan::find($pustakawanId);

                if (!$pustakawan) {
                    continue;
                }

                $sks = (int) ($sksData[$pustakawanId] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | AMBIL VALUE CHECKBOX
                |--------------------------------------------------------------------------
                */

                $tJabatanId = (int) ($data['t_jabatan_id'] ?? 0);

                $tPengabdianId = (int) ($data['t_pengabdian_id'] ?? 0);

                $tKehadiranId = (int) ($data['t_kehadiran_id'] ?? 0);

                $tTunkelId = (int) ($data['t_tunkel_id'] ?? 0);

                $tAnakId = (int) ($data['t_anak_id'] ?? 0);

                $tKehormatanId = (int) ($data['t_kehormatan_id'] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | SKIP JIKA TIDAK ADA INPUT
                |--------------------------------------------------------------------------
                */

                $adaInput =
                    $tJabatanId > 0 ||
                    $tPengabdianId > 0 ||
                    $tKehadiranId > 0 ||
                    $tTunkelId > 0 ||
                    $tAnakId > 0 ||
                    $tKehormatanId > 0 ||
                    $sks > 0;

                if (!$adaInput) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | RANK DOSEN
                |--------------------------------------------------------------------------
                */

                $rankDosenId = 0;

                if (!empty($pustakawan->tmt_mengajar)) {

                    $tmt = Carbon::parse($pustakawan->tmt_mengajar)
                        ->startOfYear();

                    $sekarang = now()->startOfYear();

                    $tahunMengajar =
                        $tmt->diffInYears($sekarang) + 1;

                    $rankDosen = DB::table('barokah_rank_dosen')

                        ->where('APBM', $apbm)

                        ->where(
                            'pendidikan_terakhir',
                            $pustakawan->pend_terakhir
                        )

                        ->where('tahun', '<=', $tahunMengajar)

                        ->orderByDesc('tahun')

                        ->first();

                    if ($rankDosen) {
                        $rankDosenId = $rankDosen->id;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN
                |--------------------------------------------------------------------------
                */

                BarokahStruktural::updateOrCreate(

                    [
                        'pustakawan_id' => $pustakawanId,
                        'periode'       => $periode,
                    ],

                    [
                        't_jabatan_id'    => $tJabatanId,
                        't_pengabdian_id' => $tPengabdianId,
                        't_kehadiran_id'  => $tKehadiranId,
                        't_tunkel_id'     => $tTunkelId,
                        't_anak_id'       => $tAnakId,
                        't_kehormatan_id' => $tKehormatanId,

                        'rank_dosen_id'   => $rankDosenId,

                        'sks'             => $sks,
                    ]
                );
            }

            DB::commit();

            return back()->with(
                'success',
                'Data barokah pustakawan berhasil disimpan.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Gagal menyimpan: ' . $e->getMessage()
            );
        }
    }
}
