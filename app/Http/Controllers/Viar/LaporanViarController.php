<?php

namespace App\Http\Controllers\Viar;

use App\Http\Controllers\Controller;
use App\Models\Master\Pustakawan;
use App\Models\Barokah\BarokahKhidmah; // Tetap gunakan model ini jika master nominal ditaruh di sini
use App\Models\Master\Libur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanViarController extends Controller
{
    public function viarPDF(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');
        $periode   = Carbon::create($tahun, $bulan, 1)->format('Y-m');

        // URL QR Code dinamis langsung mengarah ke link unduh PDF Viar bulan & tahun ini
        $qrUrl = route('viar.cetak', [
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        // 1. Ambil nominal master barokah khusus tipe 'viar' (atau 'tenaga_khidmah' jika nominal disamakan)
        $masterBarokah = BarokahKhidmah::where('tipe_barokah', 'viar')->latest()->first();
        $nominalBarokah = $masterBarokah ? $masterBarokah->barokah : 15000; // Default 15.000 jika kosong

        $jadwalMaster = DB::table('pustakawan_jadwal')->get();

        // 2. Ambil list range tanggal dalam sebulan
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );
        $dates = [];
        foreach ($period as $dt) {
            $dates[] = $dt->format('Y-m-d');
        }

        // 3. Ambil data Personil Kru Viar Aktif (Kunci: Hanya ruang_id 6 & 7)
        $pustakawan = Pustakawan::where('status', 1)
            ->where('tmt', '<=', $endDate)
            ->whereIn('ruang_id', [6, 7]) // <-- Filter khusus Kru Viar
            ->orderBy('nama_pustakawan', 'asc')
            ->get();

        $pustakawanIds = $pustakawan->pluck('id')->toArray();

        // 4. Ambil data Izin Kru Viar melalui Join Tabel Pivot
        $izinRaw = DB::table('izin_pustakawans')
            ->join('izin_pustakawan_jadwal', 'izin_pustakawans.id', '=', 'izin_pustakawan_jadwal.izin_pustakawan_id')
            ->join('jadwals', 'izin_pustakawan_jadwal.jadwal_id', '=', 'jadwals.id')
            ->whereBetween('izin_pustakawan_jadwal.tanggal', [$startDate, $endDate])
            ->whereIn('izin_pustakawans.pustakawan_id', $pustakawanIds) // <-- Filter izin hanya untuk anak Viar
            ->select(
                'izin_pustakawans.pustakawan_id',
                'izin_pustakawans.keterangan as tipe_izin',
                'izin_pustakawan_jadwal.tanggal',
                'jadwals.jadwal as shift'
            )
            ->get();

        $izinViar = [];
        foreach ($izinRaw as $row) {
            $shiftKey = strtolower(trim($row->shift)); // siang / malam

            $ket = match (strtolower(trim($row->tipe_izin))) {
                'izin', 'izin_khidmah', 'viar' => 'I',
                'sakit'                        => 'S',
                'libur'                        => 'L',
                default                        => 'I',
            };
            $izinViar[$row->pustakawan_id][$row->tanggal][$shiftKey] = $ket;
        }

        // 5. Hitung total alokasi kewajiban shift DAN buat matriks jadwal aktif/libur Kru Viar
        $totalShiftWajib = [];
        $matriksJadwal = [];

        foreach ($pustakawan as $p) {
            $jadwalP = $jadwalMaster->where('pustakawan_id', $p->id);
            $wajib = 0;

            foreach ($dates as $tgl) {
                $hariIndo = Carbon::parse($tgl)->locale('id')->translatedFormat('l');
                $match = $jadwalP->firstWhere('hari', $hariIndo);

                $isSiangAktif = $match && $match->siang == 1;
                $isMalamAktif = $match && $match->malam == 1;

                $matriksJadwal[$p->id][$tgl]['siang'] = $isSiangAktif;
                $matriksJadwal[$p->id][$tgl]['malam'] = $isMalamAktif;

                if ($isSiangAktif) $wajib++;
                if ($isMalamAktif) $wajib++;
            }
            $totalShiftWajib[$p->id] = $wajib > 0 ? $wajib : 1;
        }

        // 6. Ambil data transaksi Absensi dari tabel 'absen_viar'
        $absensiRaw = DB::table('absen_viar')
            ->join('jadwals', 'absen_viar.jadwal_id', '=', 'jadwals.id')
            ->whereBetween('absen_viar.tanggal', [$startDate, $endDate])
            ->whereIn('absen_viar.pustakawan_id', $pustakawanIds) // <-- Filter log absensi anak Viar
            ->select('absen_viar.*', 'jadwals.jadwal as nama_shift')
            ->get();

        $absensiData = [];
        $rekapKehadiran = [];

        // Inisialisasi data rekap untuk masing-masing personil agar tidak null
        foreach ($pustakawan as $p) {
            $rekapKehadiran[$p->id] = ['siang' => 0, 'malam' => 0, 'total' => 0];
        }

        foreach ($absensiRaw as $absen) {
            $shiftKey = strtolower(trim($absen->nama_shift));
            $keteranganClean = strtolower(substr(trim($absen->keterangan), 0, 1));

            // Simpan status asli ke absensiData untuk keperluan matriks halaman detail di Blade
            $absensiData[$absen->pustakawan_id][$absen->tanggal][$shiftKey] = $absen->keterangan;

            // Hitung rekap jika keterangannya adalah Hadir ('h')
            if (isset($rekapKehadiran[$absen->pustakawan_id]) && $keteranganClean === 'h') {
                if (str_contains($shiftKey, 'siang')) {
                    $rekapKehadiran[$absen->pustakawan_id]['siang']++;
                } elseif (str_contains($shiftKey, 'malam')) {
                    $rekapKehadiran[$absen->pustakawan_id]['malam']++;
                }
                $rekapKehadiran[$absen->pustakawan_id]['total']++;
            }
        }

        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        // ambil data libur dari database berdasarkan bulan dan tahun aktif
        $liburs = Libur::with('jadwals')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        // 7. Render PDF Landscape khusus folder Viar
        $pdf = Pdf::loadView('admin.Viar.RekapViar.laporan_viar', compact(
            'bulan', 'tahun', 'namaBulan', 'pustakawan', 'dates', 'qrUrl', 'periode',
            'nominalBarokah', 'izinViar', 'absensiData', 'rekapKehadiran', 'totalShiftWajib',
            'jadwalMaster', 'matriksJadwal', 'liburs'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream("rekap-absensi-viar-{$namaBulan}-{$tahun}.pdf");
    }
}
