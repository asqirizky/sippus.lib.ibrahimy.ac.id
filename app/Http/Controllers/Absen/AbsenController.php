<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use App\Models\Absen\AbsenKhidmah;
use App\Models\Absen\AbsenStruktural;
use App\Models\Absen\AbsenViar;
use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use App\Models\Setting;
use App\Models\Struktural\IzinStruktural;
use App\Services\FonnteService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    public function index () {

        return view('admin.Absen.absen_room');
    }

    public function face_recognition() {

        $settings = Setting::all();

        return view('admin.Absen.face_recognition', compact('settings'));
    }

    public function absen_struktural() {
        return view('admin.Absen.absen_struktural');
    }

    public function absen_struktural_proses(Request $request)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        $pustakawan = Pustakawan::with('jabatan')
            ->where('nik', $request->nik)
            ->first();

        if (!$pustakawan) {
            return back()->with('error', 'NIK tidak ditemukan');
        }

        $nama = $pustakawan->nama_pustakawan;

        if ($pustakawan->status == 0) {
            return back()->with('error', "Maaf {$nama}, nomor ID Anda berstatus tidak aktif / tidak terdaftar.");
        }

        if ($pustakawan->jabatan && strtolower($pustakawan->jabatan->nama_jabatan) == 'tenaga khidmah') {
            return back()->with('error', "Maaf {$nama}, Nomor id anda tidak terdeteksi di ruang ini");
        }

        $now = \Carbon\Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s');

        $jadwal = Jadwal::where('jamMasuk', '<=', $jam)
            ->where('jamPulang', '>=', $jam)
            ->first();

        if (!$jadwal) {
            return back()->with('error', "Hi! {$nama}, Tidak ada jadwal aktif saat ini");
        }

        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', 'Tidak ada jadwal di hari ini');
        }

        $shiftMap = [
            'Pagi'  => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', "{$nama} tidak memiliki jadwal pada shift ini");
        }

        $cek = AbsenStruktural::where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', "Hi! {$nama} Sudah absen pada shift ini");
        }

        AbsenStruktural::create([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir',
        ]);

        return back()->with('success', "Terima kasih {$nama}, anda telah berhasil melakukan absen hari ini");
    }


    public function absen_face(Request $request)
    {
        $request->validate([
            'nik'       => 'required',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto'      => 'required|string',
        ]);

        $pustakawan = Pustakawan::with('jabatan')
            ->where('nik', $request->nik)
            ->first();

        if (!$pustakawan) {
            return back()->with('error', 'NIK tidak ditemukan');
        }

        if ($pustakawan->status == 0) {
            return back()->with('error', 'Nomor id tidak terdaftar');
        }

        if ($pustakawan->jabatan && strtolower($pustakawan->jabatan->nama_jabatan) == 'tenaga khidmah') {
            return back()->with('error', 'Nomor id anda tidak terdeteksi di ruang ini');
        }

        // Geofencing check — cocokkan dengan semua titik yang dikonfigurasi
        $settings = Setting::all();
        $locationValid = $settings->isEmpty();
        $nearestDist = null;
        foreach ($settings as $s) {
            if ($s->latitude && $s->longitude && $s->radius) {
                $distance = $this->calculateDistance(
                    $s->latitude,
                    $s->longitude,
                    $request->latitude,
                    $request->longitude
                );
                if ($distance <= $s->radius) {
                    $locationValid = true;
                    break;
                }
                if ($nearestDist === null || $distance < $nearestDist) {
                    $nearestDist = round($distance);
                }
            }
        }
        if (!$locationValid) {
            $msg = 'Anda berada di luar area absensi';
            if ($nearestDist !== null) {
                $msg .= ' (jarak ' . $nearestDist . ' m)';
            }
            return back()->with('error', $msg);
        }

        $now = \Carbon\Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s');

        // Ambil jadwal aktif
        $jadwal = Jadwal::where('jamMasuk', '<=', $jam)
            ->where('jamPulang', '>=', $jam)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Tidak ada jadwal aktif saat ini');
        }

        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', 'Tidak ada jadwal di hari ini');
        }

        $shiftMap = [
            'Pagi' => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', 'Anda tidak memiliki jadwal pada jadwal_id ini');
        }

        $cek = AbsenStruktural::where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', 'Sudah absen pada jadwal_id ini');
        }

        // Verifikasi foto cocok dengan foto master pustakawan
        if (!empty($pustakawan->foto)) {
            $verified = $this->verifyPhoto($request->foto, $pustakawan->foto);
            if (!$verified) {
                return back()->with('error', 'Foto tidak sesuai dengan data pustakawan');
            }
        }

        // Save captured photo
        $fotoName = $this->saveCapturedPhoto($request->foto);

        AbsenStruktural::create([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir',
            'foto'          => $fotoName,
            'koordinat'     => $request->latitude . ',' . $request->longitude,
        ]);

        return back()->with('success', 'Terima kasih anda telah absen hari ini');
    }

    public function absen_strkutural() {

        return view('admin.Absen.absen_struktural');
    }

    public function mandiri(Request $request) {

        $tanggal = Carbon::parse($request->input('tanggal', Carbon::now()))->format('Y-m-d');
        $now = now()->format('H:i:s');

        $pustakawan = Pustakawan::select('pustakawans.*')
            ->join('jabatans', 'pustakawans.jabatan_id', '=', 'jabatans.id')
            ->where('pustakawans.status', 1)
            ->where('jabatans.nama_jabatan', '!=', 'Tenaga Khidmah')
            ->orderBy('jabatans.eselon', 'asc')
            ->get();

        $struktural = AbsenStruktural::whereDate('tanggal', $tanggal)->get();

        $absenStruktural = AbsenStruktural::whereDate('tanggal', $tanggal)->get();
        $izin = IzinStruktural::whereDate('tanggal_mulai', '<='. $tanggal)
                                ->whereDate('tanggal_selesai', '>=', $tanggal)
                                ->get();

        $totalStruktural = Pustakawan::whereHas('jabatan', function ($q) {
            $q->where('nama_jabatan', '!=', 'Tenaga Khidmah');
        })->count();
        $hadir = $absenStruktural->count();
        $izinJumlah = $izin->count();
        $tanpaKeterangan = $totalStruktural - ($hadir + $izinJumlah);

        $pustakawan_jadwal = DB::table('pustakawan_jadwal')
            ->join('pustakawans', 'pustakawans.id', '=', 'pustakawan_jadwal.pustakawan_id')
            ->select(
                'pustakawan_jadwal.*',
                'pustakawans.nik'
            )->get();

        return view('admin.Struktural.RekapStruktural.absen_mandiri', compact(
            'tanggal',
            'tanpaKeterangan',
            'hadir',
            'izinJumlah',
            'izin',
            'struktural',
            'pustakawan',
            'pustakawan_jadwal',
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

        $pustakawan = DB::table('pustakawans')
            ->where('nik', $request->nik)
            ->first();

        if (! $pustakawan) {
            return back()->with('error', 'Pustakawan tidak ditemukan');
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

                $hari = strtolower(
                    Carbon::parse($tgl)
                        ->locale('id')
                        ->dayName
                );

                $isLibur = DB::table('liburs')
                    ->whereDate('tanggal', $tgl)
                    ->exists();

                if ($isLibur) {
                    continue;
                }

                $isIzin = DB::table('izin_strukturals')
                    ->where('pustakawan_id', $pustakawan->id)
                    ->whereDate('tanggal_mulai', '<=', $tgl)
                    ->whereDate('tanggal_selesai', '>=', $tgl)
                    ->exists();

                if ($isIzin) {
                    continue;
                }

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

                if (in_array($request->mode_hari, ['satu_full', 'banyak_full'])) {

                    foreach ($shiftMap as $shift => $aktif) {

                        // skip jika shift tidak aktif
                        if ($aktif != 1) {
                            continue;
                        }

                        // cek duplicate
                        $exists = DB::table('absen_strukturals')
                            ->where('pustakawan_id', $pustakawan->id)
                            ->where('tanggal', $tgl)
                            ->where('jadwal_id', $jadwalIds[$shift])
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        // insert
                        DB::table('absen_strukturals')->insert([
                            'pustakawan_id' => $pustakawan->id,
                            'jadwal_id'     => $jadwalIds[$shift],
                            'tanggal'       => $tgl,
                            'jam_masuk'     => now()->format('H:i:s'),
                            'keterangan'    => 'Hadir',
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }
                }

                if (in_array($request->mode_hari, ['satu_shift', 'banyak_shift'])) {

                    $selectedShifts = $request->shifts ?? [];

                    foreach ($selectedShifts as $shift) {

                        $shift = ucfirst(strtolower($shift));

                        // validasi shift
                        if (! isset($shiftMap[$shift])) {
                            continue;
                        }

                        // cek shift aktif
                        if ($shiftMap[$shift] != 1) {
                            continue;
                        }

                        // cek duplicate
                        $exists = DB::table('absen_strukturals')
                            ->where('pustakawan_id', $pustakawan->id)
                            ->where('tanggal', $tgl)
                            ->where('jadwal_id', $jadwalIds[$shift])
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        // insert
                        DB::table('absen_strukturals')->insert([
                            'pustakawan_id' => $pustakawan->id,
                            'jadwal_id'     => $jadwalIds[$shift],
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

            return back()->with('success', 'Absen berhasil disimpan');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function kirimLaporan()
    {
        $shift = 'malam';

        $today = Carbon::today();

        $absen = AbsenStruktural::with([
                'pustakawan.jabatan',
                'jadwal'
            ])
            ->whereDate('tanggal', $today)
            ->whereHas('jadwal', function ($q) use ($shift) {
                $q->where('jadwal', $shift);
            })
            ->get();

        $hadir = $absen->filter(function ($item) {

            $jabatan = strtolower(optional($item->pustakawan->jabatan)->nama_jabatan);

            return in_array(strtolower($item->keterangan), [
                    'hadir',
                    'masuk'
                ])
                && $jabatan != 'tenaga khidmah'
                && $item->pustakawan->status == 1;
        })->unique('pustakawan_id');

        $izin = IzinStruktural::with([
            'pustakawan.jabatan',
            'jadwals'
        ])->where(function ($q) use ($today) {

            $q->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today);
        })->whereHas('jadwals', function ($q) use ($shift) {

            $q->where('jadwal', $shift);
        })
        ->get()
        ->filter(function ($item) {

            $jabatan = strtolower(optional($item->pustakawan->jabatan)->nama_jabatan);

            return $jabatan != 'tenaga khidmah'
                && $item->pustakawan->status == 1;
        })->unique('pustakawan_id');

        $semuaPustakawan = Pustakawan::with('jabatan')
            ->where('status', 1)
            ->whereHas('jadwal', function ($q) use ($shift) {
                $q->where($shift, 1);
            })
            ->whereHas('jabatan', function ($q) {
                $q->whereRaw('LOWER(nama_jabatan) != ?', ['tenaga khidmah']);
            })->get();

        $sudahAbsen = collect()
            ->merge($hadir->pluck('pustakawan_id'))
            ->merge($izin->pluck('pustakawan_id'))
            ->unique();

        $tanpaKeterangan = $semuaPustakawan
            ->whereNotIn('id', $sudahAbsen);

        $pesan = "Daftar Kehadiran Umana' Perpustakaan Ibrahimy tanggal ";
        $pesan .= $today->translatedFormat('d F Y');
        $pesan .= " shift {$shift} : \n\n";

        if ($hadir->count() > 0) {

            $no = 1;
            foreach ($hadir as $item) {
                $pesan .= $no++ . " ";
                $pesan .= $item->pustakawan->nama_pustakawan . "\n";
            }
        } else {
            $pesan .= "_Tidak ada_\n";
        }

        $pesan .= "\nUmana' yang izin : \n\n";
        if ($izin->count() > 0) {
            $no = 1;
            foreach ($izin as $item) {
                $pesan .= $no++ . " ";
                $pesan .= $item->pustakawan->nama_pustakawan . "\n";
            }
        } else {
            $pesan .= "_Tidak ada izin hari ini_\n";
        }

        $pesan .= "\nTanpa Keterangan\n\n";

        if ($tanpaKeterangan->count() > 0) {
            $no = 1;
            foreach ($tanpaKeterangan as $item) {
                $pesan .= $no++ . " ";
                $pesan .= $item->nama_pustakawan . "\n";
            }
        } else {
            $pesan .= "_Tidak ada tanpa keterangan hari ini_\n";
        }

        $pesan .= "\n\n";
        $pesan .= "Pesan ini dikirim otomatis melalui ";
        $pesan .= "SIPPUS (Sistem Informasi Presensi Pustakawan) ";
        $pesan .= "Perpustakaan Ibrahimy";

        FonnteService::send(
            env('FONNTE_GROUP'),
            $pesan
        );

        return back()->with('success', 'Laporan berhasil dikirim ke WhatsApp');
    }

    public function destroy ($id) {

        $struktural = AbsenStruktural::findOrFail($id);

        $struktural->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    // ABSEN TENAGA KHIDMAH START::
    public function khidmah () {

        return view('admin.Absen.khidmah');
    }

    public function absen_khidmah(Request $request)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        $pustakawan = Pustakawan::with('jabatan')
            ->where('nik', $request->nik)
            ->first();

        if (!$pustakawan) {
            return back()->with('error', 'NIK tidak ditemukan');
        }

        if ($pustakawan->status == 0) {
            return back()->with('error', 'Nomor id tidak terdaftar');
        }

        if (!$pustakawan->jabatan || strtolower($pustakawan->jabatan->nama_jabatan) != 'tenaga khidmah') {
            return back()->with('error', 'Nomor id anda tidak terdeteksi di ruang ini');
        }

        $now = \Carbon\Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s');

        // Ambil jadwal aktif
        $jadwal = Jadwal::where('jamMasuk', '<=', $jam)
            ->where('jamPulang', '>=', $jam)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Tidak ada jadwal aktif saat ini');
        }

        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', 'Tidak ada jadwal di hari ini');
        }

        // Mapping jadwal_id
        $shiftMap = [
            'Pagi' => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', 'Anda tidak memiliki jadwal pada jadwal_id ini');
        }

        $cek = AbsenKhidmah::where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', 'Sudah absen pada jadwal_id ini');
        }

        AbsenKhidmah::create([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir'
        ]);

        return back()->with('success', 'Terima kasih anda telah absen hari ini');
    }

    public function viar () {

        return view('admin.Absen.viar');
    }

    public function absen_viar(Request $request)
    {
        $request->validate([
            'nik' => 'required'
        ]);

        $pustakawan = Pustakawan::with(['jabatan', 'ruang'])
            ->where('nik', $request->nik)
            ->whereIn('ruang_id', [6, 7])
            ->first();

        if (!$pustakawan) {
            return back()->with('error', 'Nomor id tidak terdeteksi di ruang ini');
        }

        if ($pustakawan->status == 0) {
            return back()->with('error', 'Nomor id tidak terdaftar');
        }

        if (!$pustakawan->jabatan || strtolower($pustakawan->jabatan->nama_jabatan) != 'tenaga khidmah') {
            return back()->with('error', 'Nomor id anda tidak terdeteksi di ruang ini');
        }

        $now = \Carbon\Carbon::now();
        $tanggal = $now->toDateString();
        $jam = $now->format('H:i:s');

        // Ambil jadwal aktif
        $jadwal = Jadwal::where('jamMasuk', '<=', $jam)
            ->where('jamPulang', '>=', $jam)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Tidak ada jadwal aktif saat ini');
        }

        $hari = $now->locale('id')->translatedFormat('l');

        $jadwalPustakawan = DB::table('pustakawan_jadwal')
            ->where('pustakawan_id', $pustakawan->id)
            ->where('hari', $hari)
            ->first();

        if (!$jadwalPustakawan) {
            return back()->with('error', 'Tidak ada jadwal di hari ini');
        }

        $shiftMap = [
            'Pagi' => 'pagi',
            'Siang' => 'siang',
            'Malam' => 'malam',
        ];

        $shiftAktif = $shiftMap[$jadwal->jadwal] ?? null;

        if ($shiftAktif && $jadwalPustakawan->$shiftAktif == 0) {
            return back()->with('error', 'Anda tidak memiliki jadwal pada jadwal_id ini');
        }

        $cek = AbsenViar::where('pustakawan_id', $pustakawan->id)
            ->where('tanggal', $tanggal)
            ->where('jadwal_id', $jadwal->id)
            ->first();

        if ($cek) {
            return back()->with('error', 'Sudah absen pada jadwal_id ini');
        }

        AbsenViar::create([
            'pustakawan_id' => $pustakawan->id,
            'jadwal_id'     => $jadwal->id,
            'ruang_id'      => $pustakawan->ruang_id,
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jam,
            'keterangan'    => 'Hadir'
        ]);

        return back()->with('success', 'Terima kasih sudah absen hari ini');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);

        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) *
            pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    private function verifyPhoto($capturedBase64, $masterPhotoFilename)
    {
        $masterPath = public_path('admin/assets/media/' . $masterPhotoFilename);
        if (!file_exists($masterPath)) {
            Log::warning('Foto master tidak ditemukan: ' . $masterPhotoFilename);
            return false;
        }

        if (strpos($capturedBase64, ',') !== false) {
            $capturedBase64 = substr($capturedBase64, strpos($capturedBase64, ',') + 1);
        }

        $capturedData = base64_decode($capturedBase64);
        if (!$capturedData) {
            return false;
        }

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $capturedPath = $tempDir . '/captured_' . uniqid() . '.jpg';
        $masterTempPath = $tempDir . '/master_' . uniqid() . '.jpg';

        file_put_contents($capturedPath, $capturedData);
        copy($masterPath, $masterTempPath);

        $capturedProc = $tempDir . '/captured_proc_' . uniqid() . '.jpg';
        $masterProc = $tempDir . '/master_proc_' . uniqid() . '.jpg';

        $cmd1 = "magick " . escapeshellarg($capturedPath)
            . " -gravity center -crop 50x50%+0+0 -colorspace gray -resize 256x256! "
            . escapeshellarg($capturedProc) . " 2>/dev/null";

        $cmd2 = "magick " . escapeshellarg($masterTempPath)
            . " -gravity center -crop 50x50%+0+0 -colorspace gray -resize 256x256! "
            . escapeshellarg($masterProc) . " 2>/dev/null";

        $cmdCompare = "compare -metric phash " . escapeshellarg($capturedProc)
            . " " . escapeshellarg($masterProc) . " /dev/null 2>&1";

        exec($cmd1);
        exec($cmd2);

        $distance = null;
        exec($cmdCompare, $output, $exitCode);

        if (isset($output[0]) && is_numeric(trim($output[0]))) {
            $distance = (float) trim($output[0]);
        }

        @unlink($capturedPath);
        @unlink($masterTempPath);
        @unlink($capturedProc);
        @unlink($masterProc);

        if ($distance === null) {
            Log::warning('Foto absen - gagal menjalankan ImageMagick');
            return true;
        }

        Log::info("Foto absen - phash distance: {$distance} NIK: " . request('nik'));

        return $distance <= 120;
    }

    private function saveCapturedPhoto($base64Data)
    {
        if (strpos($base64Data, ',') !== false) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
        }

        $imageData = base64_decode($base64Data);
        if (!$imageData) {
            return null;
        }

        $fileName = 'absen_' . time() . '_' . uniqid() . '.jpg';
        $filePath = public_path('admin/assets/media/' . $fileName);

        file_put_contents($filePath, $imageData);

        return $fileName;
    }
}
