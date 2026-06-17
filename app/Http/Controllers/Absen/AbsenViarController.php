<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Pustakawan;
use App\Models\Master\Jadwal;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;


class AbsenViarController extends Controller
{
    // Tampilan Halaman Utama Absen Viar
    public function viar()
    {
        return view('admin.Absen.absen_viar');
    }

    // Eksekusi Proses Absen Viar
    public function absen_viar(Request $request)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        // 1. Cari data pustakawan berdasarkan NIK
        $pustakawan = Pustakawan::where('nik', $request->nik)->first();

        if (!$pustakawan) {
            return back()->with('error', 'NIK tidak ditemukan');
        }

                // Ambil nama pustakawan untuk dipakai di notifikasi di bawah
        $nama = $pustakawan->nama_pustakawan;


        if ($pustakawan->status == 0) {
            return back()->with('error', "{$nama}!, Nomor id  anda tidak terdaftar");
        }

        if (!in_array($pustakawan->ruang_id, [6, 7])) {
            return back()->with('error', "{$nama}! Nomor id anda tidak terdeteksi di ruang Viar");
        }

        $now = Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s'); // Contoh: '23:18:33'

        // 3. Ambil Master Jadwal secara super ketat menggunakan whereTime
        $jadwal = Jadwal::where(function($query) use ($jam) {
                $query->whereTime('jamMasuk', '<=', $jam)
                    ->whereTime('jamPulang', '>=', $jam);
            })
            ->first();

        if (!$jadwal) {
            return back()->with('error', "Gagal Absen! {$nama}! berada di luar rentang jam masuk atau jam pulang jadwal aktif saat ini.");
        }

        // 4. Cek Hari (Bahasa Indonesia) untuk mencocokkan jadwal kerja berkala mereka
        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', "{$nama}!, Tidak ada jadwal di hari ini");
        }

        // Mapping string dari master jadwal ke nama kolom di tabel pustakawan_jadwal
        $shiftMap = [
            'Pagi'  => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', "{$nama}!, Anda tidak memiliki jadwal pada shift ini");
        }

        // 5. PERBAIKAN: Cek apakah kru Viar sudah melakukan absen di tabel 'absen_viar' pada hari dan shift yang sama
        // Pastikan Anda sudah mengimpor model App\Models\Viar\AbsenViar atau model penampung tabel absen_viar Anda di bagian atas controller
        $cek = DB::table('absen_viar')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', "{$nama}!, Anda Sudah absen pada shift ini");
        }

        // 6. PERBAIKAN: Insert data ke tabel 'absen_viar' lengkap dengan field 'ruang_id' sesuai struktur database Anda
        DB::table('absen_viar')->insert([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'ruang_id'      => $pustakawan->ruang_id, // Menyimpan ruang_id (6 atau 7) agar sinkron dengan data master
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir',
            'created_at'    => $now,
            'updated_at'    => $now
        ]);

        return back()->with('success', "Terima kasih {$nama}!, anda telah berhasil melakukan absen hari ini");
    }

   public function mandiri(Request $request)
    {
        $tanggal = Carbon::parse($request->input('tanggal', Carbon::now()))->format('Y-m-d');
        $now = now()->format('H:i:s');

        // 1. AMBIL DATA PERSONIL (Jika ternyata namanya di DB tetap 'Tenaga Khidmah')
        $kruViar = DB::table('pustakawans')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('pustakawans.status', 1)
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah') // Menggunakan nama jabatan 'Tenaga Khidmah' sesuai DB
            ->whereIn('pustakawans.ruang_id', [6, 7]) // Pembatas di nomor ruang Kru Viar
            ->select('pustakawans.*')
            ->orderBy('pustakawans.nama_pustakawan', 'asc')
            ->get();

        // Ambil ID personil Viar untuk filter data absen & izin
        $pustakawanIds = $kruViar->pluck('id')->toArray();

        // 2. KUERI DATA ABSENSI HARIAN (Beralih ke tabel absen_viar dan join data pelengkap)
        $absenViar = DB::table('absen_viar')
            ->join('pustakawans', 'absen_viar.pustakawan_id', '=', 'pustakawans.id')
            ->join('jadwals', 'absen_viar.jadwal_id', '=', 'jadwals.id')
            ->whereDate('absen_viar.tanggal', $tanggal)
            ->whereIn('absen_viar.pustakawan_id', $pustakawanIds)
            ->select(
                'absen_viar.*',
                'pustakawans.nik',
                'pustakawans.nama_pustakawan',
                'jadwals.jadwal'
            )
            ->get();

        // 3. KUERI DATA IZIN (Khusus personil Viar)
        $izin = DB::table('izin_pustakawans')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->whereIn('pustakawan_id', $pustakawanIds)
            ->get();

        // 4. PERBAIKAN: HITUNG STATISTIK ABSENSI (Menggunakan Distinct Kepala / NIK agar Tidak Minus)
        $totalViar = count($pustakawanIds);

        // Hitung jumlah kepala unik yang hadir pada tanggal tersebut
        $hadir = DB::table('absen_viar')
            ->whereDate('tanggal', $tanggal)
            ->whereIn('pustakawan_id', $pustakawanIds)
            ->distinct('pustakawan_id')
            ->count('pustakawan_id');

        // Hitung jumlah kepala unik yang izin/sakit pada tanggal tersebut
        $izinJumlah = DB::table('izin_pustakawans')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->whereIn('pustakawan_id', $pustakawanIds)
            ->distinct('pustakawan_id')
            ->count('pustakawan_id');

        // Gunakan fungsi max(0, ...) sebagai barikade keamanan tingkat akhir agar tidak minus
        $tanpaKeterangan = max(0, $totalViar - ($hadir + $izinJumlah));

        // 5. PERBAIKAN: AMBIL JADWAL SHIFT KHUSUS KRU VIAR (Disamakan filternya dengan langkah 1 agar modal tidak kosong)
        $viar_jadwal = DB::table('pustakawan_jadwal')
            ->join('pustakawans', 'pustakawans.id', '=', 'pustakawan_jadwal.pustakawan_id')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah') // Disamakan menjadi 'Tenaga Khidmah' sesuai data di DB Anda
            ->whereIn('pustakawans.ruang_id', [6, 7]) // Tetap kunci di Ruang 6 & 7 milik Kru Viar
            ->select('pustakawan_jadwal.*', 'pustakawans.nik')
            ->get();

        // Return ke view dengan variabel yang sudah diubah namanya agar sinkron
        return view('admin.Viar.RekapViar.absen_mandiri_viar', compact(
            'tanggal',
            'tanpaKeterangan',
            'hadir',
            'izinJumlah',
            'izin',
            'absenViar',
            'kruViar',
            'viar_jadwal'
        ));
    }

   public function proses_mandiri(Request $request)
{
    $request->validate([
        'nik'               => 'required|exists:pustakawans,nik',
        'mode_hari'         => 'required|in:satu_full,satu_shift,banyak_full,banyak_shift',
        'tanggal_mulai'     => 'required|date',
        'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_mulai',
        'shifts'            => 'nullable|array',
    ]);

    if (in_array($request->mode_hari, ['satu_full', 'satu_shift'])) {
        $request->merge([
            'tanggal_selesai' => $request->tanggal_mulai,
        ]);
    }

    // AMBIL DATA PERSONIL (KHUSUS RUANG 6 ATAU 7 - KRU VIAR)
    $pustakawan = DB::table('pustakawans')
        ->where('nik', $request->nik)
        ->whereIn('ruang_id', [6, 7])
        ->first();

    if (!$pustakawan) {
        return back()->with('error', 'Personil tidak ditemukan atau bukan bagian dari Ruang 6 & 7 (Kru Viar)');
    }

    $jadwalIds = DB::table('jadwals')
        ->pluck('id', 'jadwal')
        ->toArray();

    DB::beginTransaction();

    try {
        $periode = CarbonPeriod::create(
            $request->tanggal_mulai,
            $request->tanggal_selesai
        );

        foreach ($periode as $tanggal) {
            $tgl = $tanggal->format('Y-m-d');
            $hari = strtolower(Carbon::parse($tgl)->locale('id')->dayName);

            // Cek Hari Libur
            $isLibur = DB::table('liburs')->whereDate('tanggal', $tgl)->exists();
            if ($isLibur) {
                continue;
            }

            // Cek Izin
            $isIzin = DB::table('izin_pustakawans')
                ->where('pustakawan_id', $pustakawan->id)
                ->whereDate('tanggal_mulai', '<=', $tgl)
                ->whereDate('tanggal_selesai', '>=', $tgl)
                ->exists();

            if ($isIzin) {
                continue;
            }

            // Ambil Aturan Jadwal Kerja Shift
            $jadwal = DB::table('pustakawan_jadwal')
                ->where('pustakawan_id', $pustakawan->id)
                ->whereRaw('LOWER(hari) = ?', [$hari])
                ->first();

            if (!$jadwal) {
                continue;
            }

            $shiftMap = [
                'Pagi'  => $jadwal->pagi ?? 0,
                'Siang' => $jadwal->siang ?? 0,
                'Malam' => $jadwal->malam ?? 0,
            ];

            // MODE FULL DAY
            if (in_array($request->mode_hari, ['satu_full', 'banyak_full'])) {
                foreach ($shiftMap as $shift => $aktif) {
                    if ($aktif != 1) {
                        continue;
                    }

                    $exists = DB::table('absen_viar')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('tanggal', $tgl)
                        ->where('jadwal_id', $jadwalIds[$shift])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // PERBAIKAN: Kolom disesuaikan menjadi ruang_id sesuai tabel asli absen_viar
                    DB::table('absen_viar')->insert([
                        'pustakawan_id' => $pustakawan->id,
                        'jadwal_id'     => $jadwalIds[$shift],
                        'ruang_id'      => $pustakawan->ruang_id, // Sesuai dengan database Anda
                        'tanggal'       => $tgl,
                        'jam_masuk'     => now()->format('H:i:s'),
                        'keterangan'    => 'Hadir',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            // MODE PILIH SHIFT SPESIFIK
            if (in_array($request->mode_hari, ['satu_shift', 'banyak_shift'])) {
                $selectedShifts = $request->shifts ?? [];

                foreach ($selectedShifts as $shift) {
                    $shift = ucfirst(strtolower($shift));

                    if (!isset($shiftMap[$shift])) {
                        continue;
                    }

                    if ($shiftMap[$shift] != 1) {
                        continue;
                    }

                    $exists = DB::table('absen_viar')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('tanggal', $tgl)
                        ->where('jadwal_id', $jadwalIds[$shift])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // PERBAIKAN: Kolom disesuaikan menjadi ruang_id sesuai tabel asli absen_viar
                    DB::table('absen_viar')->insert([
                        'pustakawan_id' => $pustakawan->id,
                        'jadwal_id'     => $jadwalIds[$shift],
                        'ruang_id'      => $pustakawan->ruang_id, // Sesuai dengan database Anda
                        'tanggal'       => $tgl,
                        'jam_masuk'     => now()->format('H:i:s'),
                        'keterangan'    => 'Hadir',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        }

        DB::commit();
        return back()->with('success', 'Absen Tenaga Viar berhasil disimpan');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        // 1. Cari data absensi berdasarkan ID di tabel absen_viar
        $absen = DB::table('absen_viar')->where('id', $id)->first();

        // Jika data tidak ditemukan
        if (!$absen) {
            return back()->with('error', 'Data absensi tidak ditemukan');
        }

        // 2. Proteksi: Pastikan data yang dihapus MEMANG milik Kru Viar (Ruang 6 & 7)
        // Jika BUKAN ruang 6 atau 7, maka proses penghapusan ditolak
        $isViar = DB::table('pustakawans')
            ->where('id', $absen->pustakawan_id)
            ->whereIn('ruang_id', [6, 7])
            ->exists();

        if (!$isViar) {
            return back()->with('error', 'Gagal! Anda hanya bisa menghapus data absensi Kru Viar di halaman ini');
        }

        // 3. Eksekusi hapus jika lolos validasi
        DB::table('absen_viar')->where('id', $id)->delete();

        return back()->with('success', 'Data absensi Kru Viar berhasil dihapus');
    }
}
