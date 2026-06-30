<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use App\Models\Absen\AbsenStruktural;
use App\Models\Master\Jadwal;
use App\Models\Master\Pustakawan;
use App\Models\Master\Ruang;
use App\Models\Setting;
use App\Models\Struktural\IzinStruktural;
use App\Models\Struktural\IzinStrukturalJadwal;
use App\Models\WaSession;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaNotifController extends Controller
{
    public function index() {

        $koordinat = Setting::get();

        return view('admin.Struktural.Geofencing.geofencing', compact(
            'koordinat',
        ));
    }

    public function create_geofencing()
    {
        $ruang = Ruang::get();
        $koordinat = Setting::first();

        $lokasi = $koordinat->lokasi ?? '';
        $lat = $koordinat->latitude ?? '';
        $lng = $koordinat->longitude ?? '';
        $radius = $koordinat->radius ?? '';

        $riwayatPresensi = AbsenStruktural::latest()->take(10)->get();

        return view('admin.Struktural.Geofencing.create_geofencing', compact(
            'lokasi',
            'ruang',
            'lat',
            'lng',
            'radius',
            'riwayatPresensi',
            'koordinat'
        ));
    }

    public function update_geofencing($id)
    {

        $ruang = Ruang::get();

        $koordinat = Setting::findOrFail($id);

        $lat = $koordinat->latitude;
        $lng = $koordinat->longitude;
        $radius = $koordinat->radius;

        $riwayatPresensi = AbsenStruktural::latest()
            ->take(10)
            ->get();

        return view('admin.Struktural.Geofencing.update_geofencing', compact(
                'ruang',
                'koordinat',
                'lat',
                'lng',
                'radius',
                'riwayatPresensi'
            )
        );
    }

    public function store(Request $request)
    {
        Setting::create([
            'ruang_id'   => $request->ruang_id,
            'latitude'   => $request->latitude,
            'longitude' => $request->longitude,
            'radius'     => $request->radius,
        ]);

        return redirect('admin/struktural-geofencing')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id) {

        $koordinat = Setting::findOrFail($id);

        $koordinat->update([
            'ruang_id'  => $request->ruang_id,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius,
        ]);

        return redirect('admin/struktural-geofencing')->with('success', 'Data berhasil di update');
    }

    // Proses Simpan Perubahan dari Map/Form Dashboard
    public function webhook(Request $request)
    {

        Log::info('WEBHOOK MASUK', [
            'all' => $request->all()
        ]);

        $pesanMasuk = strtolower($request->message ?? '');
        $pengirim = $request->sender ?? '';
        $timestamp = $request->timestamp ?? 0;

        // Abaikan request GET / ping awal Fonnte
        if ($request->isMethod('get') || empty($pengirim)) {
            return response('OK', 200);
        }

        // Filter pesan lama
        $waktuSekarang = time();

        // if ($timestamp > 0 && ($waktuSekarang - $timestamp) > 180) {
        //     return response('OK', 200);
        // }

        // Anti spam
        $messageId = 'msg_' . $pengirim . '_' . $timestamp;

        if (Cache::has($messageId) && $timestamp != '') {
            return response('OK', 200);
        }

        if ($timestamp != '') {
            Cache::put($messageId, true, now()->addMinutes(10));
        }

        Log::info('PESAN MASUK', [
            'pengirim' => $pengirim,
            'pesan' => $pesanMasuk,
            'lokasi' => $request->location,
        ]);

        $balasan = "";

        $session = WaSession::where('nomor', $pengirim)->first();

        // =========================
        // COMMAND PRIORITAS
        // =========================

        // batal
        if (in_array($pesanMasuk, ['batal', 'cancel'])) {

            if ($session) {
                $session->delete();
            }

            $balasan = "Proses izin berhasil dibatalkan.\n\nKetik *izin* untuk memulai lagi.";
        }

        // hadir
        elseif ($pesanMasuk === 'hadir') {

            $balasan = "*Presensi Kehadiran*\n\n"
                . "Silakan kirim lokasi Anda sekarang.";
        }

        // salam
        elseif (in_array($pesanMasuk, ['hai', 'halo'])) {

            $jam = now()->hour;

            if ($jam >= 5 && $jam < 11) {
                $salam = 'Pagi';
            } elseif ($jam >= 11 && $jam < 15) {
                $salam = 'Siang';
            } elseif ($jam >= 15 && $jam < 18) {
                $salam = 'Sore';
            } else {
                $salam = 'Malam';
            }

            $balasan = "Selamat {$salam}\n\n"
                . "Ketik *Hadir* untuk melakukan presensi.\n"
                . "Ketik *Izin* untuk mengajukan izin.";
        }

        // jika ada balasan dari command prioritas,
        // langsung kirim dan hentikan proses
        if ($balasan != '') {

            Log::info('MENGIRIM BALASAN PRIORITAS', [
                'target' => $pengirim,
                'message' => $balasan
            ]);

            $token = env('FONNTE_TOKEN');

            Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $pengirim,
                'message' => $balasan,
            ]);

            return response('OK', 200);
        }

        // =========================
        // PRESENSI DENGAN LOKASI
        // =========================
        if ($request->location != null || str_contains($pesanMasuk, 'loc:')) {

            $koordinat = $request->location ?? $pesanMasuk;

            $userCoords = explode(',', $koordinat);

            $userLat = trim($userCoords[0] ?? 0);
            $userLong = trim($userCoords[1] ?? 0);

            Log::info('KOORDINAT USER', [
                'lat' => $userLat,
                'long' => $userLong
            ]);

            // =========================
            // AMBIL SEMUA KOORDINAT
            // =========================
            $settings = Setting::all();

            $bolehAbsen = false;
            $jarakUser = 0;
            $lokasiDipakai = null;

            foreach ($settings as $setting) {

                $centerLat = $setting->latitude;
                $centerLong = $setting->longitude;
                $maxRadius = $setting->radius ?? 100;

                $jarak = $this->calculateDistance(
                    $centerLat,
                    $centerLong,
                    $userLat,
                    $userLong
                );

                Log::info('CEK LOKASI', [
                    'lokasi' => $setting->lokasi,
                    'jarak_meter' => $jarak,
                    'radius' => $maxRadius
                ]);

                // Jika salah satu lokasi memenuhi radius
                if ($jarak <= $maxRadius) {

                    $bolehAbsen = true;
                    $jarakUser = $jarak;
                    $lokasiDipakai = $setting->lokasi;

                    break;
                }
            }

            // =========================
            // VALIDASI RADIUS
            // =========================
            if (!$bolehAbsen) {

                $balasan = "*Maaf Presensi Ditolak*\n"
                    . "Anda berada di luar area presensi.";

            } else {

                try {

                    $nomor = preg_replace('/[^0-9]/', '', $pengirim);

                    // Ubah 62 menjadi 0
                    if (str_starts_with($nomor, '62')) {
                        $nomor = '0' . substr($nomor, 2);
                    }

                    $pustakawan = Pustakawan::whereRaw(
                        "REPLACE(no_wa, ' ', '') LIKE ?",
                        ['%' . substr($nomor, -10)]
                    )->first();

                    Log::info('DATA PUSTAKAWAN FULL', [
                        'nomor_dicari' => $nomor,
                        'pustakawan' => $pustakawan ? $pustakawan->toArray() : null,
                    ]);

                    if (!$pustakawan) {

                        $balasan = "*Gagal Menyimpan Data*\n\n"
                            . "Nomor WhatsApp Anda belum terdaftar.";

                    } else {

                        $jamSekarang = now()->format('H:i:s');

                        $jadwal = Jadwal::whereTime('jamMasuk', '<=', $jamSekarang)
                            ->whereTime('jamPulang', '>=', $jamSekarang)
                            ->first();

                        if (!$jadwal) {

                            $balasan = "*Presensi Gagal!*\n"
                                . "Sekarang bukan jam kantor.";

                        } else {

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

                            $jadwalPustakawan = DB::table('pustakawan_jadwal')
                                ->where('pustakawan_id', $pustakawan->id)
                                ->where('hari', $hari)
                                ->first();

                            if (!$jadwalPustakawan) {

                                $balasan = "*Presensi Gagal!*\n"
                                    . "Anda tidak memiliki jadwal pada hari {$hari}.";

                            } else {

                                $shift = $jadwal->jadwal;
                                $kolomShift = strtolower($shift);

                                if (
                                    !isset($jadwalPustakawan->$kolomShift) ||
                                    $jadwalPustakawan->$kolomShift != 1
                                ) {
                                    $balasan = "*Presensi Gagal!*\n"
                                    . "Anda tidak memiliki shift {$shift} pada hari {$hari}.";
                                } else {
                                    $sudahAbsen = AbsenStruktural::where('pustakawan_id', $pustakawan->id)
                                        ->whereDate('tanggal', today())
                                        ->whereHas('jadwal', function ($q) use ($shift) {
                                            $q->where('jadwal', $shift);
                                        })
                                        ->exists();

                                    if ($sudahAbsen) {

                                        $balasan = "*Tidak perlu absen dua kali.*\n"
                                        . "Anda sudah melakukan presensi shift {$shift} hari ini.";

                                    } else {

                                        AbsenStruktural::create([
                                            'pustakawan_id' => $pustakawan->id,
                                            'jadwal_id' => $jadwal->id,
                                            'tanggal' => now()->toDateString(),
                                            'jam_masuk' => now()->format('H:i:s'),
                                            'keterangan' => 'Hadir',
                                        ]);

                                        Log::info('ABSEN BERHASIL', [
                                            'nama_pustakawan' => $pustakawan->nama_pustakawan,
                                            'shift' => $shift,
                                        ]);

                                        $balasan = "*Presensi Berhasil!*\n\n"
                                            . "Nama: {$pustakawan->nama_pustakawan}\n"
                                            . "Shift: {$shift}\n"
                                            . "Tanggal: " . now()->format('d-m-Y') . "\n"
                                            . "Jam: " . now()->format('H:i') . "\n"
                                            . "Jarak: " . round($jarakUser) . " meter\n\n"
                                            . "Terima kasih sudah melakukan absensi hari ini."
                                            . " Semoga seluruh urusan Anda diberi kelancaraan oleh Allah Subhanahu Wata'ala";
                                    }
                                }
                            }
                        }
                    }

                } catch (\Exception $e) {

                    Log::error('GAGAL ABSEN', [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                    ]);

                    $balasan = "*Presensi Gagal!*\n\n"
                        . "Terjadi kesalahan sistem.";
                }
            }

        } elseif ($pesanMasuk === 'izin') {

            try {

                WaSession::updateOrCreate(
                    ['nomor' => $pengirim],
                    [
                        'step' => 'pilih_keterangan',
                        'keterangan' => null,
                        'mode' => null,
                        'tanggal_mulai' => null,
                        'tanggal_selesai' => null,
                        'jadwal_id' => null,
                        'shift_mapping' => null,
                    ]
                );

                $balasan = "*Pilih keterangan izin*\n"
                    . "1. Izin\n"
                    . "2. Tugas Pesantren\n"
                    . "3. Sakit\n\n"
                    . "Ketikan angka untuk memilih keterangan izin";

            } catch (\Exception $e) {

                Log::error('ERROR MENU IZIN', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
                ]);

                $balasan = "Terjadi kesalahan sistem.";
            }

        } elseif ($session && $session->step == 'pilih_keterangan') {

            $data = [
                '1' => 'Izin',
                '2' => 'Tugas Pesantren',
                '3' => 'Sakit',
            ];

            if (!isset($data[$pesanMasuk])) {

                $balasan = "Pilihan tidak valid.";

            } else {

                $session->update([
                    'keterangan' => $data[$pesanMasuk],
                    'step' => 'pilih_mode'
                ]);

                $balasan = "*Pilih mode hari*\n\n"
                    . "1. Satu Hari (Full)\n"
                    . "2. Satu Hari (Shift)\n"
                    . "3. Beberapa Hari (Full)\n"
                    . "4. Beberapa Hari (Shift)\n\n"
                    . "Ketik angka untuk memilih mode hari";
            }

        } elseif ($session && $session->step == 'pilih_mode') {

            switch ($pesanMasuk) {

                case '1':

                    $session->update([
                        'mode' => 'satu_hari_full',
                        'step' => 'tanggal_satu_hari_full'
                    ]);

                    $balasan =
                        "Masukkan tanggal izin\n\n"
                        . "_Contoh: 12 Juni 2026_";

                    break;

                case '2':

                    $nomor = preg_replace('/[^0-9]/', '', $pengirim);

                    if (str_starts_with($nomor, '62')) {
                        $nomor = '0' . substr($nomor, 2);
                    }

                    $pustakawan = Pustakawan::whereRaw(
                        "REPLACE(no_wa,' ','') LIKE ?",
                        ['%' . substr($nomor, -10)]
                    )->first();

                    $hari = now()->locale('id')->translatedFormat('l');

                    $jadwal = DB::table('pustakawan_jadwal')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('hari', $hari)
                        ->first();

                    if (!$jadwal) {

                        $balasan = "Jadwal hari ini tidak ditemukan.";
                        break;
                    }

                    $mapping = [];
                    $no = 1;

                    $balasan = "*Silahkan ketik angka untuk memilih shift*\n\n";

                    $jadwalPagi  = Jadwal::where('jadwal', 'Pagi')->first();
                    $jadwalSiang = Jadwal::where('jadwal', 'Siang')->first();
                    $jadwalMalam = Jadwal::where('jadwal', 'Malam')->first();

                    $jadwalPagi  = Jadwal::where('jadwal', 'Pagi')->first();
                    $jadwalSiang = Jadwal::where('jadwal', 'Siang')->first();
                    $jadwalMalam = Jadwal::where('jadwal', 'Malam')->first();

                    if ($jadwal->pagi == 1 && $jadwalPagi) {
                        $balasan .= $no . ". Pagi\n";
                        $mapping[$no] = $jadwalPagi->id;
                        $no++;
                    }

                    if ($jadwal->siang == 1 && $jadwalSiang) {
                        $balasan .= $no . ". Siang\n";
                        $mapping[$no] = $jadwalSiang->id;
                        $no++;
                    }

                    if ($jadwal->malam == 1 && $jadwalMalam) {
                        $balasan .= $no . ". Malam\n";
                        $mapping[$no] = $jadwalMalam->id;
                        $no++;
                    }

                    $session->update([
                        'mode' => 'satu_hari_shift',
                        'step' => 'pilih_shift_satu_hari',
                        'shift_mapping' => json_encode($mapping)
                    ]);

                    break;

                case '3':

                    $session->update([
                        'mode' => 'beberapa_hari_full',
                        'step' => 'tanggal_beberapa_hari_full'
                    ]);

                    $balasan =
                        "Ketikan tanggal mulai izin sampai selesai izin\n\n"
                        . "_contoh: 1 Juni 2026 s/d 5 Juni 2026_";

                    break;

                case '4':

                    $session->update([
                        'mode' => 'beberapa_hari_shift',
                        'step' => 'tanggal_beberapa_hari_shift'
                    ]);

                    $balasan =
                        "Ketikan tanggal mulai izin sampai selesai izin\n\n"
                        . "_contoh: 1 Juni 2026 s/d 5 Juni 2026_";

                    break;

                default:
                    $balasan = "Pilihan tidak valid.";
            }

        } elseif ($session && $session->step == 'pilih_shift_satu_hari') {

            $mapping = json_decode($session->shift_mapping, true);

            if (!isset($mapping[$pesanMasuk])) {

                $balasan = "Pilihan shift tidak valid.";

            } else {

                $jadwalId = $mapping[$pesanMasuk];

                $session->update([
                    'jadwal_id' => $jadwalId,
                    'step' => 'tanggal_satu_hari_shift'
                ]);

                $balasan =
                    "Ketik tanggal izin\n\n"
                    . "_Contoh: 1 Juni 2026_";
            }

        } elseif ($session && $session->step == 'tanggal_satu_hari_full') {

            try {

                $tanggal = Carbon::parse($request->message)->format('Y-m-d');

                $session->update([
                    'tanggal_mulai' => $tanggal,
                    'tanggal_selesai' => $tanggal
                ]);

                $this->simpanIzinWA($pengirim, $session);

                $balasan = "*Izin berhasil diproses*\n\n"
                    . "Keterangan : {$session->keterangan}\n"
                    . "Tanggal : "
                    . Carbon::parse($tanggal)->format('d-m-Y');

                $session->delete();

            } catch (\Throwable $e) {

                Log::error('ERROR TANGGAL IZIN', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine()
                ]);

                $balasan = "Format tanggal tidak valid.";
            }
        } elseif ($session && $session->step == 'tanggal_satu_hari_shift') {

            $tanggal = Carbon::parse($request->message)->format('Y-m-d');

            $session->update([
                'tanggal_mulai' => $tanggal,
                'tanggal_selesai' => $tanggal
            ]);

            $this->simpanIzinWA($pengirim, $session);

            $jadwal = Jadwal::find($session->jadwal_id);

            $balasan = "*Izin berhasil diproses*\n\n"
                . "Keterangan : {$session->keterangan}\n"
                . "Shift : " . ($jadwal->jadwal ?? '-') . "\n"
                . "Tanggal : "
                . Carbon::parse($tanggal)->format('d-m-Y');

            $session->delete();

        } elseif ($session && $session->step == 'tanggal_beberapa_hari_full') {

            [$mulai, $selesai] = explode('s/d', strtolower($request->message));

            $session->update([
                'tanggal_mulai' => Carbon::parse(trim($mulai))->format('Y-m-d'),
                'tanggal_selesai' => Carbon::parse(trim($selesai))->format('Y-m-d')
            ]);

            $this->simpanIzinWA($pengirim, $session);

            $balasan = "*Izin berhasil diproses*\n\n"
                . "Keterangan : {$session->keterangan}\n"
                . "Tanggal : "
                . Carbon::parse($session->tanggal_mulai)->format('d-m-Y')
                . " s/d "
                . Carbon::parse($session->tanggal_selesai)->format('d-m-Y');

            $session->delete();

        } elseif ($session && $session->step == 'tanggal_beberapa_hari_shift') {

            [$mulai, $selesai] = explode('s/d', strtolower($request->message));

            $session->update([
                'tanggal_mulai' => Carbon::parse(trim($mulai))->format('Y-m-d'),
                'tanggal_selesai' => Carbon::parse(trim($selesai))->format('Y-m-d'),
                'step' => 'pilih_shift_beberapa_hari'
            ]);

            $nomor = preg_replace('/[^0-9]/', '', $pengirim);

            if (str_starts_with($nomor, '62')) {
                $nomor = '0' . substr($nomor, 2);
            }

            $pustakawan = Pustakawan::whereRaw(
                "REPLACE(no_wa,' ','') LIKE ?",
                ['%' . substr($nomor, -10)]
            )->first();

            $hari = now()->locale('id')->translatedFormat('l');

            $jadwal = DB::table('pustakawan_jadwal')
                ->where('pustakawan_id', $pustakawan->id)
                ->where('hari', $hari)
                ->first();

            if (!$jadwal) {
                $balasan = "Jadwal hari ini tidak ditemukan.";
            } else {
                $mapping = [];
                $balasan = "*Silahkan ketik angka untuk memilih shift*\n\n";
                $no = 1;

                $jadwalPagi  = Jadwal::where('jadwal', 'Pagi')->first();
                $jadwalSiang = Jadwal::where('jadwal', 'Siang')->first();
                $jadwalMalam = Jadwal::where('jadwal', 'Malam')->first();

                if ($jadwal->pagi == 1 && $jadwalPagi) {

                    $balasan .= $no . ". Pagi\n";
                    $mapping[$no] = [
                        'id' => $jadwalPagi->id,
                        'nama' => 'Pagi'
                    ];

                    $no++;
                }

                if ($jadwal->siang == 1 && $jadwalSiang) {

                    $balasan .= $no . ". Siang\n";

                    $mapping[$no] = [
                        'id' => $jadwalSiang->id,
                        'nama' => 'Siang'
                    ];

                    $no++;
                }

                if ($jadwal->malam == 1 && $jadwalMalam) {

                    $balasan .= $no . ". Malam\n";

                    $mapping[$no] = [
                        'id' => $jadwalMalam->id,
                        'nama' => 'Malam'
                    ];

                    $no++;
                }

                if (empty($mapping)) {
                    $balasan = "Anda tidak memiliki jadwal shift pada hari ini.";
                } else {
                    $session->update([
                        'shift_mapping' => json_encode($mapping)
                    ]);
                }
            }

        } elseif ($session && $session->step == 'pilih_shift_beberapa_hari') {

            $mapping = json_decode($session->shift_mapping, true);

            if (!isset($mapping[$pesanMasuk])) {

                $balasan = "Pilihan shift tidak valid.";

            } else {

                $pilihan = $mapping[$pesanMasuk];

                $session->update([
                    'jadwal_id' => $pilihan['id'],
                    'shift_mapping' => json_encode([
                        'nama_shift' => $pilihan['nama']
                    ])
                ]);

                $this->simpanIzinWA($pengirim, $session);

                $balasan = "*Izin berhasil diproses*\n\n"
                    . "Keterangan : {$session->keterangan}\n"
                    . "Shift : {$pilihan['nama']}\n"
                    . "Tanggal : "
                    . Carbon::parse($session->tanggal_mulai)->format('d-m-Y')
                    . " s/d "
                    . Carbon::parse($session->tanggal_selesai)->format('d-m-Y');

                $session->delete();
            }

        }

        if ($balasan != "") {

            Log::info('MENGIRIM BALASAN', [
                'target' => $pengirim,
                'message' => $balasan
            ]);

            $token = env('FONNTE_TOKEN');

            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $pengirim,
                'message' => $balasan,
            ]);

            Log::info('RESPON FONNTE', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }

        return response('OK', 200);
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

    private function simpanIzinWA($pengirim, $session)
    {
        $nomor = preg_replace('/[^0-9]/', '', $pengirim);

        if (str_starts_with($nomor, '62')) {
            $nomor = '0' . substr($nomor, 2);
        }

        $pustakawan = Pustakawan::whereRaw(
            "REPLACE(no_wa,' ','') LIKE ?",
            ['%' . substr($nomor, -10)]
        )->first();

        if (!$pustakawan) {
            throw new \Exception('Pustakawan tidak ditemukan');
        }

        DB::transaction(function () use ($session, $pustakawan) {

            $izin = IzinStruktural::create([
                'pustakawan_id'   => $pustakawan->id,
                'tanggal_mulai'   => $session->tanggal_mulai,
                'tanggal_selesai' => $session->tanggal_selesai,
                'keterangan'      => $session->keterangan,
            ]);

            $periode = CarbonPeriod::create(
                $session->tanggal_mulai,
                $session->tanggal_selesai
            );

            foreach ($periode as $tanggal) {

                // izin per shift
                if (
                    in_array(
                        $session->mode,
                        ['satu_hari_shift', 'beberapa_hari_shift']
                    )
                ) {

                    IzinStrukturalJadwal::create([
                        'izin_struktural_id' => $izin->id,
                        'jadwal_id'          => $session->jadwal_id,
                        'tanggal'            => $tanggal->format('Y-m-d'),
                    ]);

                } else {

                    $hariMap = [
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                    ];

                    $hari = $hariMap[$tanggal->format('l')];

                    $jadwal = DB::table('pustakawan_jadwal')
                        ->where('pustakawan_id', $pustakawan->id)
                        ->where('hari', $hari)
                        ->first();

                    if (!$jadwal) {
                        continue;
                    }

                    $pagiId = Jadwal::where('jadwal', 'Pagi')->value('id');
                    $siangId = Jadwal::where('jadwal', 'Siang')->value('id');
                    $malamId = Jadwal::where('jadwal', 'Malam')->value('id');

                    if ($jadwal->pagi == 1) {
                        IzinStrukturalJadwal::create([
                            'izin_struktural_id' => $izin->id,
                            'jadwal_id'          => $pagiId,
                            'tanggal'            => $tanggal->format('Y-m-d'),
                        ]);
                    }

                    if ($jadwal->siang == 1) {
                        IzinStrukturalJadwal::create([
                            'izin_struktural_id' => $izin->id,
                            'jadwal_id'          => $siangId,
                            'tanggal'            => $tanggal->format('Y-m-d'),
                        ]);
                    }

                    if ($jadwal->malam == 1) {
                        IzinStrukturalJadwal::create([
                            'izin_struktural_id' => $izin->id,
                            'jadwal_id'          => $malamId,
                            'tanggal'            => $tanggal->format('Y-m-d'),
                        ]);
                    }
                }
            }
        });
    }

    // destroy geofencing
    public function destroy($id) {

        $koordinat = Setting::findOrFail($id);

        $koordinat->delete();

        return back()->with('success', 'Data berhasil di hapus');
    }
}
