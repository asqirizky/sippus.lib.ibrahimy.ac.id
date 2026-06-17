<?php

namespace App\Http\Controllers\Viar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Pustakawan;
use App\Models\Izin\IzinPustakawan;

class RekapViarController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input tanggal, default ke hari ini jika kosong
        $tanggalInput = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $tanggal = Carbon::parse($tanggalInput)->format('Y-m-d');

        // 2. Ambil seluruh master personil Kru Viar yang aktif (Ruang 6 & 7) sebagai acuan utama
        $kruViar = Pustakawan::where('status', 1)
            ->whereIn('ruang_id', [6, 7])
            ->get();

        $totalViar = $kruViar->count();
        $pustakawanIds = $kruViar->pluck('id')->toArray();

        // 3. PERBAIKAN: KUERI DATA ABSENSI HARIAN (Lengkap dengan JOIN agar data Nama, NIK, dan Shift muncul di tabel)
        $absenViar = DB::table('absen_viar')
            ->join('pustakawans', 'absen_viar.pustakawan_id', '=', 'pustakawans.id')
            ->join('jadwals', 'absen_viar.jadwal_id', '=', 'jadwals.id')
            ->whereIn('absen_viar.pustakawan_id', $pustakawanIds)
            ->whereDate('absen_viar.tanggal', $tanggal)
            ->select(
                'absen_viar.*',
                'pustakawans.nik',
                'pustakawans.nama_pustakawan',
                'jadwals.jadwal'
            )
            ->get();

        // 4. Ambil data izin Kru Viar yang aktif di tanggal tersebut
        $izin = IzinPustakawan::whereIn('pustakawan_id', $pustakawanIds)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get();

        // 5. Kalkulasi statistik rekapitulasi harian (Berdasarkan jumlah kepala/orang unik)
        $hadir = $absenViar->unique('pustakawan_id')->count();
        $izinJumlah = $izin->unique('pustakawan_id')->count();

        // Rumus Alfa yang akurat (Menggunakan max untuk barikade keamanan)
        $tanpaKeterangan = max(0, $totalViar - ($hadir + $izinJumlah));

        // 6. Return ke view Rekap Viar beserta datanya
        return view('admin.Viar.RekapViar.rekap_viar', compact(
            'kruViar',
            'absenViar',
            'tanpaKeterangan',
            'tanggal',
            'izin',
            'izinJumlah',
            'hadir',
            'totalViar'
        ));
    }
}
