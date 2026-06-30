<?php

namespace App\Console\Commands;

use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Pustakawan;
use App\Models\Struktural\IzinStruktural;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KirimLaporanShift extends Command
{
    protected $signature = 'laporan:shift {shift}';

    protected $description = 'Kirim laporan absensi per shift ke WhatsApp';

    public function handle()
    {
        try {

            $shift = strtolower($this->argument('shift'));
            $today = Carbon::today();
            $hari = strtolower(
                Carbon::now()
                    ->locale('id')
                    ->dayName
            );

            // =========================
            // DATA ABSEN
            // =========================
            $absen = AbsenStruktural::with([
                    'pustakawan.jabatan',
                    'jadwal'
                ])
                ->whereDate('tanggal', $today)

                ->whereHas('jadwal', function ($q) use ($shift) {

                    $q->where('jadwal', $shift);

                })
                ->get();

            // =========================
            // DATA HADIR
            // =========================
            $hadir = $absen->filter(function ($item) {

                $jabatan = strtolower(
                    optional(
                        optional($item->pustakawan)
                            ->jabatan
                    )->nama_jabatan
                );

                return in_array(
                        strtolower($item->keterangan),
                        ['hadir', 'masuk']
                    )
                    && $jabatan != 'tenaga khidmah'
                    && optional($item->pustakawan)->status == 1;

            })->unique('pustakawan_id');

            // =========================
            // DATA IZIN
            // =========================
            $izin = IzinStruktural::with([
                'pustakawan.jabatan',
                'jadwals.jadwal'
            ])

            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)

            ->get()

            // FILTER SHIFT & TANGGAL
            ->filter(function ($item) use ($shift, $today) {

                return $item->jadwals->contains(
                    function ($jadwalPivot) use ($shift, $today) {

                        // cek tanggal pivot
                        if (
                            Carbon::parse($jadwalPivot->tanggal)
                                ->toDateString()
                            != $today->toDateString()
                        ) {
                            return false;
                        }

                        // cek shift
                        return strtolower(
                            optional(
                                $jadwalPivot->jadwal
                            )->jadwal
                        ) == strtolower($shift);
                    }
                );
            })

            // FILTER STATUS
            ->filter(function ($item) {

                $jabatan = strtolower(
                    optional(
                        optional($item->pustakawan)
                            ->jabatan
                    )->nama_jabatan
                );

                return $jabatan != 'tenaga khidmah'
                    && optional($item->pustakawan)
                        ->status == 1;
            })

            ->unique('pustakawan_id');

            // =========================
            // SEMUA PUSTAKAWAN
            // =========================
            $semuaPustakawan = Pustakawan::with('jabatan')

                ->where('status', 1)

                ->whereHas(
                    'jadwal',
                    function ($q) use ($shift, $hari) {

                        $q->where('hari', $hari)
                        ->where($shift, 1);

                    }
                )

                ->whereHas(
                    'jabatan',
                    function ($q) {

                        $q->whereRaw(
                            'LOWER(nama_jabatan) != ?',
                            ['tenaga khidmah']
                        );
                    }
                )

                ->get();

            // =========================
            // YANG SUDAH ABSEN / IZIN
            // =========================
            $sudahAbsen = collect()

                ->merge(
                    $hadir->pluck('pustakawan_id')
                )

                ->merge(
                    $izin->pluck('pustakawan_id')
                )

                ->unique();

            // =========================
            // TANPA KETERANGAN
            // =========================
            $tanpaKeterangan = $semuaPustakawan
                ->whereNotIn('id', $sudahAbsen);

            // =========================
            // FORMAT PESAN
            // =========================
            $pesan = "Daftar Kehadiran Umana' ";
            $pesan .= "Perpustakaan Ibrahimy tanggal ";
            $pesan .= $today->translatedFormat('d F Y');
            $pesan .= " shift {$shift}\n\n";

            // =========================
            // HADIR
            // =========================
            $pesan .= "Hadir :\n";

            if ($hadir->count() > 0) {

                $no = 1;

                foreach ($hadir as $item) {

                    $nama = optional(
                        $item->pustakawan
                    )->nama_pustakawan;

                    $pesan .= $no++ . ". ";
                    $pesan .= "{$nama}\n";
                }

            } else {

                $pesan .= "_Tidak ada_\n";
            }

            // =========================
            // IZIN
            // =========================
            $pesan .= "\nIzin :\n";

            if ($izin->count() > 0) {

                $no = 1;

                foreach ($izin as $item) {

                    $nama = optional(
                        $item->pustakawan
                    )->nama_pustakawan;

                    $keterangan = ucwords(
                        strtolower(
                            trim($item->keterangan ?? 'Izin')
                        )
                    );

                    $pesan .= $no++ . ". ";
                    $pesan .= "{$nama} ({$keterangan})\n";
                }

            } else {

                $pesan .= "_Tidak ada izin hari ini_\n";
            }

            // =========================
            // TANPA KETERANGAN
            // =========================
            $pesan .= "\nTanpa Keterangan :\n";

            if ($tanpaKeterangan->count() > 0) {

                $no = 1;

                foreach ($tanpaKeterangan as $item) {

                    $pesan .= $no++ . ". ";
                    $pesan .= "{$item->nama_pustakawan}\n";
                }

            } else {

                $pesan .= "_Tidak ada tanpa keterangan hari ini_\n";
            }

            // =========================
            // FOOTER
            // =========================
            $pesan .= "\n\n";
            $pesan .= "Pesan ini dikirim secara otomatis melalui ";
            $pesan .= "SIPPUS (Sistem Informasi Presensi ";
            $pesan .= "Pustakawan) Perpustakaan Ibrahimy";

            // =========================
            // KIRIM WHATSAPP
            // =========================
            $response = FonnteService::send(
                env('FONNTE_GROUP'),
                $pesan
            );

            Log::info('LAPORAN SHIFT TERKIRIM', [
                'shift' => $shift,
                'tanggal' => $today->toDateString(),
                'response' => $response
            ]);

            $this->info(
                "Laporan shift {$shift} berhasil dikirim"
            );

        } catch (\Exception $e) {

            Log::error(
                'GAGAL KIRIM LAPORAN SHIFT: '
                . $e->getMessage()
            );

            Log::error($e->getTraceAsString());

            $this->error($e->getMessage());
        }
    }
}
