<?php

namespace App\Console\Commands;

use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Pustakawan;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimPengingatShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengingat:shift {shift}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        // Ambil pustakawan yang belum presensi
        $semuaPustakawan = Pustakawan::with('jabatan')
            ->where('status', 1)
            ->whereNotNull('no_wa')
            ->whereRaw("TRIM(no_wa) != ''")
            ->whereNotIn('id', $sudahAbsen)
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

            $pesan = "*Assalamu'alaikum Wr. Wb.*\n\n";
            $pesan .= "Yth. {$pustakawan->nama_pustakawan}\n\n";
            $pesan .= "Anda memiliki jadwal *Shift " . ucfirst($shift) . "* hari ini.\n";
            $pesan .= "Jika sudah berada di lingkungan perpustakaan, silakan segera melakukan presensi melalui aplikasi/web SIPPUS.\n";
            $pesan .= "Apabila berhalangan hadir, silakan mengirim *Izin*.\n\n";
            $pesan .= "Terima kasih.\n\n";
            $pesan .= "_Pesan ini dikirim secara otomatis oleh SIPPUS (Sistem Informasi Presensi Pustakawan)._";

            FonnteService::send($nomor, $pesan);

            // Jeda 0,5 detik
            usleep(500000);
        }

        $this->info("Pengingat berhasil dikirim kepada {$semuaPustakawan->count()} pustakawan.");
    }
}
