<?php

namespace App\Http\Controllers\Struktural;

use App\Http\Controllers\Controller;
use App\Models\Master\Pustakawan;
use App\Models\Struktural\IzinStruktural;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinStrukturalController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $pustakawan = Pustakawan::select('pustakawans.*')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('pustakawans.status', 1)
            ->whereRaw('LOWER(jabatans.nama_jabatan) != ?', ['tenaga khidmah'])
            ->orderBy('jabatans.eselon', 'asc')
            ->get();

        $izinStruktural = IzinStruktural::with('pustakawan')
            ->where(function ($query) use ($bulan, $tahun) {

                $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

                $query->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                    ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                    ->orWhere(function ($q) use ($awalBulan, $akhirBulan) {
                            $q->where('tanggal_mulai', '<=', $awalBulan)
                            ->where('tanggal_selesai', '>=', $akhirBulan);
                    });
            })->get();

        $pustakawan_jadwal = DB::table('pustakawan_jadwal')->get();

        return view('admin.Struktural.IzinStruktural.izin_struktural', compact(
            'izinStruktural',
            'pustakawan',
            'pustakawan_jadwal',
            'bulan',
            'tahun',
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'pustakawan_id'   => 'required|exists:pustakawans,id',
                'keterangan'      => 'required',
                'mode_hari'       => 'required|in:satu_full,satu_shift,banyak_full,banyak_shift',
                'tanggal_mulai'   => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'shifts'          => 'nullable|array',
                'shifts.*'        => 'in:pagi,siang,malam',
            ]);

            if (in_array($request->mode_hari, ['satu_shift','banyak_shift']) && empty($request->shifts)) {
                throw new \Exception('Mode shift wajib memilih minimal 1 shift');
            }

            $izin = IzinStruktural::create([
                'pustakawan_id'   => $request->pustakawan_id,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'keterangan'      => $request->keterangan,
            ]);

            $jadwalMap = DB::table('jadwals')
                ->pluck('id', 'jadwal')
                ->mapWithKeys(fn($id, $key) => [strtolower($key) => $id])
                ->toArray();

            $start = Carbon::parse($request->tanggal_mulai);
            $end   = Carbon::parse($request->tanggal_selesai);

            while ($start <= $end) {

                $hari = strtolower($start->translatedFormat('l'));

                // ambil jadwal pustakawan di hari itu
                $jadwal = DB::table('pustakawan_jadwal')
                    ->where('pustakawan_id', $request->pustakawan_id)
                    ->get()
                    ->first(fn($j) => strtolower($j->hari) === $hari);

                if (!$jadwal) {
                    $start->addDay();
                    continue;
                }

                $jadwalDipilih = [];

                if (in_array($request->mode_hari, ['satu_shift','banyak_shift'])) {

                    foreach ($request->shifts as $shift) {

                        // hanya ambil shift yang memang aktif di jadwal
                        if ($jadwal->$shift == 1) {

                            if (!isset($jadwalMap[$shift])) {
                                throw new \Exception("Mapping jadwal {$shift} tidak ditemukan");
                            }

                            $jadwalDipilih[] = $jadwalMap[$shift];
                        }
                    }
                }

                else {

                    if ($jadwal->pagi == 1 && isset($jadwalMap['pagi'])) {
                        $jadwalDipilih[] = $jadwalMap['pagi'];
                    }

                    if ($jadwal->siang == 1 && isset($jadwalMap['siang'])) {
                        $jadwalDipilih[] = $jadwalMap['siang'];
                    }

                    if ($jadwal->malam == 1 && isset($jadwalMap['malam'])) {
                        $jadwalDipilih[] = $jadwalMap['malam'];
                    }
                }

                if (empty($jadwalDipilih)) {
                    $start->addDay();
                    continue;
                }

                foreach ($jadwalDipilih as $jadwal_id) {

                    DB::table('izin_struktural_jadwal')->insert([
                        'izin_struktural_id' => $izin->id,
                        'jadwal_id'          => $jadwal_id,
                        'tanggal'            => $start->format('Y-m-d'),
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }

                $start->addDay();
            }

            DB::commit();

            return back()->with('success', 'Izin berhasil disimpan');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy ($id) {

        $izinStruktural = IzinStruktural::findOrFail($id);

        $izinStruktural->delete();

        return back()->with('success', 'Data berhasil di hapus');
    }
}
