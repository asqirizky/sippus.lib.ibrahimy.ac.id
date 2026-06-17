<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absen\AbsenKhidmah;
use App\Models\Master\Pustakawan;
use App\Models\Master\Jadwal;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;


class AbsenTenagaKhidmahController extends Controller
{
    // Tampilan Halaman Utama Absen
    public function khidmah()
    {
        return view('admin.Absen.absen_khidmah');
    }

    // Eksekusi Proses Absen
    public function absen_khidmah(Request $request)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        // 1. Cari data pustakawan berdasarkan NIK beserta jabatannya
        $pustakawan = Pustakawan::with('jabatan')
            ->where('nik', $request->nik)
            ->first();

        if (!$pustakawan) {
        return back()->with('error', 'NIK tidak ditemukan');
        }
        // Ambil nama pustakawan untuk dipakai di notifikasi di bawah
        $nama = $pustakawan->nama_pustakawan;

        if ($pustakawan->status == 0) {
        return back()->with('error', "Maaf {$nama}, nomor ID Anda berstatus tidak aktif / tidak terdaftar.");
        }

        // 2. Proteksi Ruang: Pastikan hanya untuk Tenaga Khidmah
        if (!$pustakawan->jabatan || strtolower($pustakawan->jabatan->nama_jabatan) != 'tenaga khidmah') {
            return back()->with('error', "Maaf {$nama}, Nomor id anda tidak terdeteksi di ruang Tenaga Khidmah silahkan cari di ruang lain");
        }



        // BLOKIR ANAK VIAR (RUANG 6 & 7): Jika ruangnya 6 atau 7, tendang keluar!
        if (in_array($pustakawan->ruang_id, [6, 7])) {
            return back()->with('error', "Maaf {$nama}, Nomor id anda terdaftar sebagai Tenaga Viar, Silahkan absen di menu Viar!");
        }

        $now = Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s');

        // 3. Ambil Master Jadwal berdasarkan rentang jam saat ini
        $jadwal = Jadwal::where('jamMasuk', '<=', $jam)
            ->where('jamPulang', '>=', $jam)
            ->first();

        if (!$jadwal) {
            return back()->with('error', "Hi! {$nama}, Tidak ada jadwal aktif saat ini");
        }

        // 4. Cek Hari (Bahasa Indonesia) untuk mencocokkan jadwal kerja berkala mereka
        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', 'Tidak ada jadwal di hari ini');
        }

        // Mapping string dari master jadwal ke nama kolom di tabel pustakawan_jadwal
        $shiftMap = [
            'Pagi'  => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', "{$nama} tidak memiliki jadwal pada shift ini");
        }

        // 5. Cek apakah pustakawan sudah melakukan absen pada hari dan shift (jadwal_id) yang sama
        $cek = AbsenKhidmah::where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', "Hi! {$nama} Sudah absen pada shift ini");
        }

        // 6. Insert data ke tabel absen_khidmahs sesuai dengan struktur phpMyAdmin Anda
        AbsenKhidmah::create([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'jabatan_id'    => $pustakawan->jabatan_id, // Mengambil ID Jabatan Tenaga Khidmah milik pustakawan
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir'
        ]);

        return back()->with('success', "Terima kasih {$nama}, anda telah berhasil melakukan absen hari ini");
    }

    public function mandiri(Request $request)
    {
        $tanggal = Carbon::parse($request->input('tanggal', Carbon::now()))->format('Y-m-d');
        $now = now()->format('H:i:s');

        // 1. AMBIL DATA PERSONIL KHUSUS TENAGA KHIDMAH (KECUALI RUANG 6 DAN 7)
        $tenagaKhidmah = DB::table('pustakawans')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('pustakawans.status', 1)
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah')
            ->whereNotIn('pustakawans.ruang_id', [6, 7])
            ->select('pustakawans.*')
            ->orderBy('pustakawans.nama_pustakawan', 'asc')
            ->get();

        // 2. Kueri data log absensi harian Tenaga Khidmah (Menampilkan semua log untuk tabel)
        $absenKhidmah = DB::table('absen_khidmah')
            ->join('pustakawans', 'absen_khidmah.pustakawan_id', '=', 'pustakawans.id')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->join('jadwals', 'absen_khidmah.jadwal_id', '=', 'jadwals.id') // Tambahkan JOIN ini
            ->whereDate('absen_khidmah.tanggal', $tanggal)
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah')
            ->whereNotIn('pustakawans.ruang_id', [6, 7])
            ->select(
                'absen_khidmah.*',
                'pustakawans.nama_pustakawan',
                'pustakawans.nik',
                'jadwals.jadwal as jadwal' // Ambil string "Siang" / "Malam" dari master jadwal
            )
            ->get();

        // 3. Kueri data izin khusus Tenaga Khidmah
        $izin = DB::table('izin_pustakawans')
            ->join('pustakawans', 'izin_pustakawans.pustakawan_id', '=', 'pustakawans.id')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->whereDate('izin_pustakawans.tanggal_mulai', '<=', $tanggal)
            ->whereDate('izin_pustakawans.tanggal_selesai', '>=', $tanggal)
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah')
            ->whereNotIn('pustakawans.ruang_id', [6, 7])
            ->select('izin_pustakawans.*', 'pustakawans.nama_pustakawan', 'pustakawans.nik')
            ->get();

        // 4. HITUNG TOTAL PERSONIL KHIDMAH AKTIF (Pasti Berjumlah 9 Orang)
        $totalKhidmah = $tenagaKhidmah->count();

        // FIX UTAMA: Menghitung jumlah ORANG unik yang hadir, bukan jumlah SHIFT/BARIS DATA
        // Menggunakan unique('pustakawan_id') agar id personil yang sama tidak dihitung dua kali
        $hadir = $absenKhidmah->unique('pustakawan_id')->count();

        // Serta pastikan izin juga unik jika ada input ganda (jika diperlukan)
        $izinJumlah = $izin->unique('pustakawan_id')->count();

        // 5. Hitung sisa personil yang belum ada keterangan sama sekali hari ini
        $tanpaKeterangan = $totalKhidmah - ($hadir + $izinJumlah);

        // Antisipasi jika ada keadaan tak terduga, nilai minimal adalah 0 (tidak minus)
        if ($tanpaKeterangan < 0) {
            $tanpaKeterangan = 0;
        }

        // 6. AMBIL JADWAL SHIFT KHUSUS TENAGA KHIDMAH
        $khidmah_jadwal = DB::table('pustakawan_jadwal')
            ->join('pustakawans', 'pustakawans.id', '=', 'pustakawan_jadwal.pustakawan_id')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('jabatans.nama_jabatan', '=', 'Tenaga Khidmah')
            ->whereNotIn('pustakawans.ruang_id', [6, 7])
            ->select('pustakawan_jadwal.*', 'pustakawans.nik', 'pustakawans.nama_pustakawan')
            ->get();

        return view('admin.TenagaKhidmah.RekapKhidmah.absen_mandiri_tenaga_khidmah', compact(
            'tanggal',
            'tanpaKeterangan',
            'hadir',
            'izinJumlah',
            'izin',
            'absenKhidmah',
            'tenagaKhidmah',
            'khidmah_jadwal'
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

    // Ambil data personil sekaligus validasi pengecualian Ruang 6 dan 7
    $pustakawan = DB::table('pustakawans')
        ->where('nik', $request->nik)
        ->whereNotIn('ruang_id', [6, 7])
        ->first();

    if (!$pustakawan) {
        return back()->with('error', 'Personil Tenaga Khidmah tidak ditemukan atau berada di ruang pengecualian');
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

            // Cek Izin Tenaga Khidmah
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

                    $exists = DB::table('absen_khidmah')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('tanggal', $tgl)
                        ->where('jadwal_id', $jadwalIds[$shift])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('absen_khidmah')->insert([
                        'pustakawan_id' => $pustakawan->id,
                        'jadwal_id'     => $jadwalIds[$shift],
                        'jabatan_id'    => $pustakawan->jabatan_id,
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

                    $exists = DB::table('absen_khidmah')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('tanggal', $tgl)
                        ->where('jadwal_id', $jadwalIds[$shift])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // PERBAIKAN: Ditambahkan kolom 'jabatan_id' agar seimbang dengan skema database
                    DB::table('absen_khidmah')->insert([
                        'pustakawan_id' => $pustakawan->id,
                        'jadwal_id'     => $jadwalIds[$shift],
                        'jabatan_id'    => $pustakawan->jabatan_id, // <-- Selesai diperbaiki
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
        return back()->with('success', 'Absen Tenaga Khidmah berhasil disimpan');

    } catch (\Throwable $e) {
        DB::rollBack();
        // Mengembalikan error spesifik ke session untuk mempermudah debugging jika ada kendala lain
        return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        // 1. Cari data absensi berdasarkan ID yang dikirim
        $absen = DB::table('absen_khidmah')->where('id', $id)->first();

        // Jika data tidak ditemukan
        if (!$absen) {
            return back()->with('error', 'Data absensi tidak ditemukan');
        }

        // 2. Proteksi Tambahan: Pastikan data yang dihapus bukan milik Kru Viar (Ruang 6 & 7)
        $isViar = DB::table('pustakawans')
            ->where('id', $absen->pustakawan_id)
            ->whereIn('ruang_id', [6, 7])
            ->exists();

        if ($isViar) {
            return back()->with('error', 'Gagal! Anda tidak bisa menghapus data absensi Kru Viar melalui halaman ini');
        }

        // 3. Eksekusi hapus jika lolos validasi
        DB::table('absen_khidmah')->where('id', $id)->delete();

        return back()->with('success', 'Data absensi berhasil dihapus');
    }
}

