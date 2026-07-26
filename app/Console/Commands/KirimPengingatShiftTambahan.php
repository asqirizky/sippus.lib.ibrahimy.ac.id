<?php

namespace App\Console\Commands;

use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Pustakawan;
use App\Models\Struktural\IzinStruktural;
use App\Models\Izin\IzinPustakawan;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimPengingatShiftTambahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengingat:shift-tambahan {shift}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat tambahan kepada pustakawan yang belum absen atau izin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shift = strtolower($this->argument('shift'));
        $today = Carbon::today();

        // Ambil ID pustakawan yang sudah presensi pada shift ini hari ini
        $sudahAbsen = AbsenStruktural::whereDate('tanggal', $today)
            ->whereHas('jadwal', function ($q) use ($shift) {
                $q->whereRaw('LOWER(jadwal) = ?', [$shift]);
            })
            ->pluck('pustakawan_id');

        // Ambil ID pustakawan yang sudah mengajukan izin pada shift ini hari ini
        $sudahIzinStruktural = IzinStruktural::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->whereHas('jadwals', function ($q) use ($shift) {
                $q->whereHas('jadwal', function ($q2) use ($shift) {
                    $q2->whereRaw('LOWER(jadwal) = ?', [$shift]);
                });
            })
            ->pluck('pustakawan_id');

        $sudahIzinPustakawan = IzinPustakawan::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->whereHas('jadwals', function ($q) use ($shift) {
                $q->whereHas('jadwal', function ($q2) use ($shift) {
                    $q2->whereRaw('LOWER(jadwal) = ?', [$shift]);
                });
            })
            ->pluck('pustakawan_id');

        $sudahIzin = $sudahIzinStruktural->merge($sudahIzinPustakawan);

        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        $hari = $hariMap[now()->format('l')];

        // Ambil pustakawan yang belum presensi dan belum izin
        $semuaPustakawan = Pustakawan::with('jabatan')
            ->where('status', 1)
            ->whereNotNull('no_wa')
            ->whereRaw("TRIM(no_wa) != ''")
            ->whereNotIn('id', $sudahAbsen)
            ->whereNotIn('id', $sudahIzin)
            ->whereHas('jadwal', function ($q) use ($hari, $shift) {
                $q->where('hari', $hari)
                ->where($shift, 1);
            })
            ->whereHas('jabatan', function ($q) {
                $q->whereRaw('LOWER(nama_jabatan) != ?', ['tenaga khidmah']);
            })
            ->get();

        foreach ($semuaPustakawan as $pustakawan) {

            $nomor = preg_replace('/[^0-9]/', '', $pustakawan->no_wa);

            if (str_starts_with($nomor, '0')) {
                $nomor = '62' . substr($nomor, 1);
            }

            $pesan = "*Pemberitahuan*\n\n";
            $pesan .= "Yth. {$pustakawan->nama_pustakawan} ({$pustakawan->nik})\n\n";
            $pesan .= "Anda memiliki jadwal *Shift " . ucfirst($shift) . "* hari ini.\n";
            $pesan .= "Hingga saat ini anda belum melakukan *presensi* atau *izin*.\n\n";
            $pesan .= "Silakan segera lakukan salah satu:\n";
            $pesan .= "1. *Presensi* melalui link: sippus.lib.ibrahimy.ac.id/absen-face\n";
            $pesan .= "2. *Izin* jika berhalangan hadir\n\n";
            $pesan .= "Mohon perhatiannya, terima kasih.\n\n";
            $pesan .= "_Pesan ini dikirim secara otomatis oleh SIPPUS (Sistem Informasi Presensi Pustakawan)._";

            FonnteService::send($nomor, $pesan);

            // Jeda 0,5 detik
            usleep(500000);
        }

        $this->info("Pengingat tambahan berhasil dikirim kepada {$semuaPustakawan->count()} pustakawan.");
    }
}
