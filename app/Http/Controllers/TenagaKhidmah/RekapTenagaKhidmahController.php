<?php

namespace App\Http\Controllers\TenagaKhidmah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\Pustakawan;
use App\Models\Absen\AbsenKhidmah;
use App\Models\Izin\IzinPustakawan;

class RekapTenagaKhidmahController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input tanggal, default ke hari ini jika kosong
        $tanggalInput = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $tanggal = Carbon::parse($tanggalInput)->format('Y-m-d');

        // 2. Ambil data absensi KHUSUS Tenaga Khidmah (Bukan Viar) pada tanggal tersebut
        $absenKhidmah = AbsenKhidmah::with('pustakawan')
            ->whereDate('tanggal', $tanggal)
            ->whereHas('pustakawan', function ($q) {
                $q->where('status', 1)
                  ->where('jabatan_id', 26) // Khusus Tenaga Khidmah
                  ->whereNotIn('ruang_id', [6, 7]); // Kunci: Kecualikan Anak Viar
            })
            ->get();

        // 3. Ambil data izin KHUSUS tipe 'tenaga_khidmah' dan Bukan Anak Viar yang aktif di tanggal tersebut
        $izin = IzinPustakawan::with('pustakawan')
            ->where('tipe_izin', 'tenaga_khidmah') // Kunci: Hanya filter tipe khidmah agar tidak bocor tipe lain
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->whereHas('pustakawan', function ($q) {
                $q->whereNotIn('ruang_id', [6, 7]); // Kunci: Kecualikan Anak Viar
            })
            ->get();

        // 4. Hitung total seluruh personil Tenaga Khidmah yang aktif (Tanpa Anak Viar)
        $totalKhidmah = Pustakawan::where('status', 1)
            ->where('jabatan_id', 26) // Menggunakan ID jabatan langsung agar lebih cepat & akurat
            ->whereNotIn('ruang_id', [6, 7]) // Kunci: Kecualikan Anak Viar
            ->count();

        // 5. Kalkulasi statistik rekapitulasi harian
        $hadir = $absenKhidmah->count();
        $izinJumlah = $izin->count();

        // Rumus Alfa / Tanpa Keterangan yang sekarang dijamin presisi
        $tanpaKeterangan = $totalKhidmah - ($hadir + $izinJumlah);

        // Mencegah nilai minus jika ada anomali data double absen
        if ($tanpaKeterangan < 0) {
            $tanpaKeterangan = 0;
        }

        // 6. Return ke view Rekap Tenaga Khidmah beserta datanya
        return view('admin.TenagaKhidmah.RekapKhidmah.rekap_tenaga_khidmah', compact(
            'absenKhidmah',
            'tanpaKeterangan',
            'tanggal',
            'izin',
            'izinJumlah',
            'hadir',
            'totalKhidmah'
        ));
    }
}
