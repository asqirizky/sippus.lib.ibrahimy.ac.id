<?php

namespace App\Http\Controllers\TenagaKhidmah;

use App\Http\Controllers\Controller;
use App\Models\Master\Pustakawan;
use App\Models\Barokah\BarokahKhidmah;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Master\Libur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTenagaKhidmahController extends Controller
{
    public function tenagaKhidmahPDF(Request $request)
    {
        $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');
        $periode   = Carbon::create($tahun, $bulan, 1)->format('Y-m');

        // URL QR Code dinamis langsung mengarah ke link unduh PDF bulan & tahun ini
        $qrUrl = route('tenaga-khidmah.cetak', [
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        // 1. Ambil nominal master barokah (Khidmah)
        $masterBarokah = BarokahKhidmah::where('tipe_barokah', 'tenaga_khidmah')->latest()->first();
        $nominalBarokah = $masterBarokah ? $masterBarokah->barokah : 0;

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

        // 3. Ambil data Personil Khidmah Aktif (Kunci: Mengecualikan kru Viar id ruang 6 & 7)
        $pustakawan = Pustakawan::where('status', 1)
            // Data lama tidak selalu mempunyai TMT. Personel aktif tersebut tetap
            // harus muncul di laporan, selama belum melewati periode laporan.
            ->where(function ($query) use ($endDate) {
                $query->whereNull('tmt')->orWhere('tmt', '<=', $endDate);
            })
            ->whereHas('jabatan', function($q) {
                $q->whereRaw('LOWER(nama_jabatan) = ?', ['tenaga khidmah']);
            })
            // NULL berarti belum ditempatkan di ruang; jangan sampai ikut hilang.
            ->where(function ($query) {
                $query->whereNull('ruang_id')->orWhereNotIn('ruang_id', [6, 7]);
            })
            ->orderBy('nama_pustakawan', 'asc')
            ->get();

        $pustakawanIds = $pustakawan->pluck('id');

        // 4. Ambil data Izin melalui Join Tabel Pivot (Filter agar anak Viar tidak ikut ketarik)
        $izinRaw = DB::table('izin_pustakawans')
            ->join('izin_pustakawan_jadwal', 'izin_pustakawans.id', '=', 'izin_pustakawan_jadwal.izin_pustakawan_id')
            ->join('jadwals', 'izin_pustakawan_jadwal.jadwal_id', '=', 'jadwals.id')
            ->join('pustakawans', 'izin_pustakawans.pustakawan_id', '=', 'pustakawans.id') // Join ke pustakawan untuk filter ruang
            ->whereBetween('izin_pustakawan_jadwal.tanggal', [$startDate, $endDate])
            ->whereIn('izin_pustakawans.pustakawan_id', $pustakawanIds)
            ->select(
                'izin_pustakawans.pustakawan_id',
                'izin_pustakawans.keterangan as tipe_izin',
                'izin_pustakawan_jadwal.tanggal',
                'jadwals.jadwal as shift'
            )
            ->get();

        $izinKhidmah = [];
        foreach ($izinRaw as $row) {
            $shiftKey = strtolower(trim($row->shift)); // siang / malam

            $ket = match (strtolower(trim($row->tipe_izin))) {
                'izin', 'izin_khidmah' => 'I',
                'sakit'                => 'S',
                'libur'                => 'L',
                default                => 'I',
            };
            $izinKhidmah[$row->pustakawan_id][$row->tanggal][$shiftKey] = $ket;
        }

       // 5. Hitung total alokasi kewajiban shift DAN buat matriks jadwal aktif/libur
        $totalShiftWajib = [];
        $matriksJadwal = []; // Penampung pasokan data jadwal untuk Blade

        foreach ($pustakawan as $p) {
            $jadwalP = $jadwalMaster->where('pustakawan_id', $p->id);
            $wajib = 0;

            foreach ($dates as $tgl) {
                // KUNCI: Ambil nama hari dalam Bahasa Indonesia (Senin, Selasa, Rabu, dst.)
                // agar cocok dengan isi kolom 'hari' di tabel pustakawan_jadwal Anda
                $hariIndo = Carbon::parse($tgl)->locale('id')->translatedFormat('l');

                // Cari data jadwal yang sesuai dengan pustakawan_id dan nama hari
                $match = $jadwalP->firstWhere('hari', $hariIndo);

                // Jika data ada dan kolom bernilai 1 berarti TRUE (ada jadwal), jika 0 atau null berarti FALSE (libur)
                $isSiangAktif = $match && $match->siang == 1;
                $isMalamAktif = $match && $match->malam == 1;

                // Masukkan ke dalam matriks komponen
                $matriksJadwal[$p->id][$tgl]['siang'] = $isSiangAktif;
                $matriksJadwal[$p->id][$tgl]['malam'] = $isMalamAktif;

                // Tambahkan ke kalkulasi total shift wajib jika statusnya aktif (1)
                if ($isSiangAktif) $wajib++;
                if ($isMalamAktif) $wajib++;
            }
            $totalShiftWajib[$p->id] = $wajib > 0 ? $wajib : 1;
        }

        // 6. Ambil data transaksi Absensi Khidmah (Filter agar absensi anak Viar tidak masuk)
        $absensiRaw = DB::table('absen_khidmah')
            ->join('jadwals', 'absen_khidmah.jadwal_id', '=', 'jadwals.id')
            ->join('pustakawans', 'absen_khidmah.pustakawan_id', '=', 'pustakawans.id')
            ->whereBetween('absen_khidmah.tanggal', [$startDate, $endDate])
            ->whereIn('absen_khidmah.pustakawan_id', $pustakawanIds)
            ->select('absen_khidmah.*', 'jadwals.jadwal as nama_shift')
            ->get();

        $absensiData = [];
        $rekapKehadiran = [];

        // Inisialisasi data rekap untuk masing-masing personil agar tidak null
        foreach ($pustakawan as $p) {
            $rekapKehadiran[$p->id] = ['siang' => 0, 'malam' => 0, 'total' => 0];
        }

        foreach ($absensiRaw as $absen) {
            // Bersihkan string shift dari spasi dan ubah ke huruf kecil
            $shiftKey = strtolower(trim($absen->nama_shift));

            // Bersihkan string keterangan (ambil karakter pertama saja untuk antisipasi kata "Hadir")
            $keteranganClean = strtolower(substr(trim($absen->keterangan), 0, 1));

            // Simpan status asli ke absensiData untuk keperluan matriks halaman 2 & 3
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

        $liburs = Libur::with('jadwals')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        // Data siap tampil untuk halaman ringkasan PDF. Perhitungan dipusatkan di
        // controller agar nama, kehadiran, dan nominal barokah selalu sejalan.
        $rekapPersonil = $pustakawan->map(function ($personil) use ($rekapKehadiran, $dates, $liburs, $matriksJadwal, $nominalBarokah) {
            $siang = $rekapKehadiran[$personil->id]['siang'] ?? 0;
            $malam = $rekapKehadiran[$personil->id]['malam'] ?? 0;
            $jumlahHadir = $rekapKehadiran[$personil->id]['total'] ?? 0;
            $targetJadwal = 0;

            foreach ($dates as $tanggal) {
                foreach (['siang' => 'Siang', 'malam' => 'Malam'] as $shiftKey => $shiftName) {
                    $libur = $liburs->contains(function ($item) use ($tanggal, $shiftName) {
                        return $item->tanggal === $tanggal
                            && $item->jadwals->contains('jadwal', $shiftName);
                    });

                    if (($matriksJadwal[$personil->id][$tanggal][$shiftKey] ?? false) && ! $libur) {
                        $targetJadwal++;
                    }
                }
            }

            return [
                'personil' => $personil,
                'siang' => $siang,
                'malam' => $malam,
                'jumlah_hadir' => $jumlahHadir,
                'persentase' => $targetJadwal > 0 ? min(100, round(($jumlahHadir / $targetJadwal) * 100)) : 0,
                'jumlah_barokah' => $jumlahHadir * $nominalBarokah,
            ];
        });

        $totalRekap = [
            'siang' => $rekapPersonil->sum('siang'),
            'malam' => $rekapPersonil->sum('malam'),
            'jumlah_hadir' => $rekapPersonil->sum('jumlah_hadir'),
            'jumlah_barokah' => $rekapPersonil->sum('jumlah_barokah'),
        ];

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        // 7. Render PDF Landscape (Tambahkan jadwalMaster ke dalam compact)
        $pdf = Pdf::loadView('admin.TenagaKhidmah.RekapKhidmah.laporan_tenaga_khidmah', compact(
            'bulan', 'tahun', 'namaBulan', 'pustakawan', 'dates', 'qrUrl', 'periode',
            'nominalBarokah', 'izinKhidmah', 'absensiData', 'rekapKehadiran', 'totalShiftWajib',
            'jadwalMaster', 'matriksJadwal', 'liburs', 'rekapPersonil', 'totalRekap'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream("rekap-absensi-khidmah-{$namaBulan}-{$tahun}.pdf");
    }
}
