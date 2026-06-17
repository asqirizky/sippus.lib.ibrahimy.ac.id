<?php

namespace App\Http\Controllers\TenagaKhidmah;

use App\Http\Controllers\Controller;
use App\Models\Master\Pustakawan;
use App\Models\Izin\IzinPustakawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinTenagaKhidmahController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // PERBAIKAN: Memfilter agar Pustakawan yang memiliki ruang_id 6 & 7 (Viar) tidak ikut tampil di sini
        $pustakawan = Pustakawan::where('status', 1)
            ->where('jabatan_id', 26)
            ->whereNotIn('ruang_id', [6, 7]) // <-- Kunci filternya di sini, Bro!
            ->orderBy('nama_pustakawan', 'asc')
            ->get();

        // Ambil data izin dari tabel BARU khusus tipe 'tenaga_khidmah'
        // Ditambahkan filter whereHas agar riwayat izin milik anak-anak Viar tidak ikut nimbrung di sini
        $izinKhidmah = IzinPustakawan::with('pustakawan')
            ->where('tipe_izin', 'tenaga_khidmah')
            ->whereHas('pustakawan', function ($query) {
                $query->whereNotIn('ruang_id', [6, 7]); // <-- Filter riwayat izin agar bersih dari anak Viar
            })
            ->where(function ($query) use ($bulan, $tahun) {
                $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

                $query->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                    ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan]);
            })->get();

        $pustakawan_jadwal = DB::table('pustakawan_jadwal')->get();

        return view('admin.TenagaKhidmah.IzinKhidmah.IzinTenagaKhidmah', compact(
            'izinKhidmah', 'pustakawan', 'pustakawan_jadwal', 'bulan', 'tahun'
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
            ]);

            // Simpan ke tabel baru, set tipe_izin secara otomatis
            $izin = IzinPustakawan::create([
                'pustakawan_id'   => $request->pustakawan_id,
                'tipe_izin'       => 'tenaga_khidmah',
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'keterangan'      => $request->keterangan,
            ]);

            $jadwalMap = DB::table('jadwals')->pluck('id', 'jadwal')
                ->mapWithKeys(fn($id, $key) => [strtolower($key) => $id])->toArray();

            $start = Carbon::parse($request->tanggal_mulai);
            $end   = Carbon::parse($request->tanggal_selesai);

            $semuaJadwal = DB::table('pustakawan_jadwal')->where('pustakawan_id', $request->pustakawan_id)->get();
            $dataInsertJadwal = [];

            while ($start <= $end) {
                $hari = strtolower($start->translatedFormat('l'));
                $jadwal = $semuaJadwal->first(fn($j) => strtolower($j->hari) === $hari);

                if (!$jadwal) { $start->addDay(); continue; }

                $jadwalDipilih = [];
                if (in_array($request->mode_hari, ['satu_shift','banyak_shift'])) {
                    foreach ($request->shifts as $shift) {
                        if ($jadwal->$shift == 1 && isset($jadwalMap[$shift])) {
                            $jadwalDipilih[] = $jadwalMap[$shift];
                        }
                    }
                } else {
                    if ($jadwal->pagi == 1 && isset($jadwalMap['pagi']))   { $jadwalDipilih[] = $jadwalMap['pagi']; }
                    if ($jadwal->siang == 1 && isset($jadwalMap['siang']))  { $jadwalDipilih[] = $jadwalMap['siang']; }
                    if ($jadwal->malam == 1 && isset($jadwalMap['malam'])) { $jadwalDipilih[] = $jadwalMap['malam']; }
                }

                foreach ($jadwalDipilih as $jadwal_id) {
                    $dataInsertJadwal[] = [
                        'izin_pustakawan_id' => $izin->id,
                        'jadwal_id'          => $jadwal_id,
                        'tanggal'            => $start->format('Y-m-d'),
                        'tipe_jadwal'        => 'tenaga_khidmah',
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];
                }
                $start->addDay();
            }

            if (!empty($dataInsertJadwal)) {
                DB::table('izin_pustakawan_jadwal')->insert($dataInsertJadwal);
            }

            DB::commit();
            return back()->with('success', 'Izin Tenaga Khidmah berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $izinKhidmah = IzinPustakawan::findOrFail($id);

        // Hapus data anak melalui relasi Eloquent
        $izinKhidmah->jadwals()->delete();

        // Hapus data induk
        $izinKhidmah->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
