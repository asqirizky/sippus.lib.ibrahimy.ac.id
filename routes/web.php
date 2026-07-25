<?php


use App\Http\Controllers\Absen\AbsenController;
use App\Http\Controllers\Absen\AbsenTenagaKhidmahController;
use App\Http\Controllers\Absen\AbsenViarController;
use App\Http\Controllers\Absen\WaNotifController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\JadwalController;
use App\Http\Controllers\Master\LiburController;
use App\Http\Controllers\Master\PustakawanController;
use App\Http\Controllers\Master\RuangController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Struktural\BarokahStrukturalController;
use App\Http\Controllers\Struktural\IzinStrukturalController;
use App\Http\Controllers\Struktural\LaporanStrukturalController;
use App\Http\Controllers\Struktural\RekapStrukturalController;
use App\Http\Controllers\TenagaKhidmah\BarokahTenagaKhidmahController;
use App\Http\Controllers\TenagaKhidmah\IzinTenagaKhidmahController; // baru izin
use App\Http\Controllers\TenagaKhidmah\LaporanTenagaKhidmahController;
use App\Http\Controllers\TenagaKhidmah\RekapTenagaKhidmahController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Viar\BarokahViarController;
use App\Http\Controllers\Viar\IzinViarController;
use App\Http\Controllers\Viar\LaporanViarController;
use App\Http\Controllers\Viar\RekapViarController;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


// Area Admin
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware([Authenticate::class])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/halo', function () {
    return "Routing Laravel Berhasil Jalan!";
});
// Middleware hanya untuk pengguna yang sudah login
Route::middleware([Authenticate::class])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('home-user');
});

// #############################################
// Route public
// #############################################

// Pustakawan Struktural Face Recognition
Route::get('/absen-face', [AbsenController::class, 'face_recognition'])->name('absen-face');
Route::post('/absen-face', [AbsenController::class, 'absen_face'])->name('struktural-proses');

// Pustakawan Struktural Biasa
Route::get('/absen-struktural', [AbsenController::class, 'absen_struktural'])->name('absen-struktural');
Route::post('/absen-struktural', [AbsenController::class, 'absen_struktural_proses'])->name('struktural-biasa-proses');

// Tenaga Khidmah
Route::get('/absen-khidmah-room', [AbsenTenagaKhidmahController::class, 'khidmah'])->name('absen-khidmah-room');
Route::post('/absen-khidmah-room', [AbsenTenagaKhidmahController::class, 'absen_khidmah'])->name('khidmah-proses-room');

// Viar
Route::get('/absen-viar-room', [AbsenViarController::class, 'viar'])->name('absen-viar');
Route::post('/absen-viar-room', [AbsenViarController::class, 'absen_viar'])->name('absen-viar-proses');

// Route dengan autentikasi - laporan dan notifikasi
Route::middleware([Authenticate::class])->group(function () {
    Route::get('/struktural-cetak/{bulan}/{tahun}', [LaporanStrukturalController::class, 'strukturalPDF'])->name('struktural.cetak');
    Route::get('/tenaga-khidmah/cetak', [LaporanTenagaKhidmahController::class, 'tenagaKhidmahPDF'])->name('tenaga-khidmah.cetak');
    Route::get('/tenaga-viar/cetak', [LaporanViarController::class, 'viarPdf'])->name('viar.cetak');
    Route::get('/kirim-laporan-struktural', [AbsenController::class, 'kirimLaporan'])->name('kirim.laporan.struktural');
});

// #############################################
// Route Admin Struktural
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':absen ruang-lihat'])->group(function () {
    Route::get('/admin/absen-ruang', [AbsenController::class, 'index'])->name('absen.ruang');
    Route::get('/admin/absen-struktural/{id}/hapus', [AbsenController::class, 'destroy'])->name('struktural-absen.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural geofencing-lihat'])->group(function () {
    Route::resource('/admin/geofencing', WaNotifController::class);
    Route::get('/admin/struktural-geofencing', [WaNotifController::class, 'index']);
    Route::get('/admin/struktural-create_geofencing', [WaNotifController::class, 'create_geofencing'])->name('geofencing.tambah');
    Route::get('/admin/struktural-update_geofencing={id}', [WaNotifController::class, 'update_geofencing'])->name('update-geofencing.lihat');
    Route::post('/admin/struktural-geofencing', [WaNotifController::class, 'store']);
    Route::put('/admin/struktural-geofencing/{id}', [WaNotifController::class, 'update'])->name('koordinat-lokasi.update');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural geofencing-hapus'])->group(function () {
    Route::get('/admin/struktural-geofencing/{id}', [WaNotifController::class, 'destroy'])->name('geofencing.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural izin-lihat'])->group(function () {
    Route::resource('/admin/struktural-izin', IzinStrukturalController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural izin-hapus'])->group(function () {
    Route::get('/admin/struktural-izin/{id}/hapus', [IzinStrukturalController::class, 'destroy'])->name('struktural-izin.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural barokah-lihat'])->group(function () {
    Route::get('/admin/struktural-barokah', [BarokahStrukturalController::class, 'index'])->name('barokah-struktural');
    Route::get('/admin/struktural-generate', [BarokahStrukturalController::class, 'generate'])->name('struktural-generate');
    Route::post('/admin/struktural-generate', [BarokahStrukturalController::class, 'store'])->name('barokah_struktural.store');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':struktural rekap-lihat'])->group(function () {
    Route::get('/admin/absen-struktural_mandiri', [AbsenController::class, 'mandiri'])->name('struktural-mandiri');
    Route::post('/admin/absen-struktural_mandiri', [AbsenController::class, 'proses_mandiri'])->name('struktural-mandiri.store');
    Route::get('/admin/struktural-rekap', [RekapStrukturalController::class, 'index'])->name('rekap.struktural');
});

// #############################################
// Route Admin Tenaga Khidmah
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':khidmah izin-lihat'])->group(function () {
    Route::get('/admin/khidmah-izin', [IzinTenagaKhidmahController::class, 'index'])->name('khidmah.izin.index');
    Route::post('/admin/khidmah-izin', [IzinTenagaKhidmahController::class, 'store'])->name('khidmah.izin.store');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':khidmah izin-hapus'])->group(function () {
    Route::get('/admin/khidmah-izin/{id}', [IzinTenagaKhidmahController::class, 'destroy'])->name('khidmah.izin.destroy');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':khidmah barokah-lihat'])->group(function () {
    Route::get('/admin/khidmah-barokah', [BarokahTenagaKhidmahController::class, 'index'])->name('khidmah.barokah.index');
    Route::post('/admin/barokah-tambah', [BarokahTenagaKhidmahController::class, 'store'])->name('khidmah.barokah.store');
    Route::put('/admin/barokah-update-khidmah/{id}', [BarokahTenagaKhidmahController::class, 'update'])->name('khidmah.barokah.update');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':khidmah barokah-hapus'])->group(function () {
    Route::get('/admin/barokah-destroy/{id}', [BarokahTenagaKhidmahController::class, 'destroy'])->name('khidmah.barokah.destroy');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':khidmah rekap-lihat'])->group(function () {
    Route::get('/admin/khidmah-rekap', [RekapTenagaKhidmahController::class, 'index'])->name('rekap.tenaga_khidmah');
    Route::get('/admin/absen-khidmah_mandiri', [AbsenTenagaKhidmahController::class, 'mandiri'])->name('khidmah-mandiri');
    Route::post('/admin/absen-khidmah_mandiri', [AbsenTenagaKhidmahController::class, 'proses_mandiri'])->name('khidmah-mandiri.store');
    Route::get('/admin/absen-khidmah/{id}/hapus', [AbsenTenagaKhidmahController::class, 'destroy'])->name('khidmah.absen.hapus');
});

// #############################################
// Route Admin Viar
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':viar izin-lihat'])->group(function () {
    Route::get('/admin/viar-izin', [IzinViarController::class, 'index'])->name('viar.izin.index');
    Route::post('/admin/viar-store', [IzinViarController::class, 'store'])->name('viar.izin.store');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':viar izin-hapus'])->group(function () {
    Route::get('/admin/viar/{id}/delete', [IzinViarController::class, 'destroy'])->name('viar.izin.destroy');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':viar barokah-lihat'])->group(function () {
    Route::get('/admin/viar-barokah', [BarokahViarController::class, 'index'])->name('viar.barokah.index');
    Route::post('/admin/barokah-viar-tambah', [BarokahViarController::class, 'store'])->name('viar.barokah.store');
    Route::put('/admin/barokah-update-viar/{id}', [BarokahViarController::class, 'update'])->name('viar.barokah.update');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':viar barokah-hapus'])->group(function () {
    Route::get('/admin/barokah-viar-destroy/{id}', [BarokahViarController::class, 'destroy'])->name('viar.barokah.destroy');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':viar rekap-lihat'])->group(function () {
    Route::get('/admin/absen-viar_mandiri', [AbsenViarController::class, 'mandiri'])->name('viar-mandiri');
    Route::post('/admin/absen-viar_mandiri', [AbsenViarController::class, 'proses_mandiri'])->name('viar-mandiri.store');
    Route::get('/admin/absen-viar/{id}/hapus', [AbsenViarController::class, 'destroy'])->name('viar.absen.hapus');
    Route::get('/admin/viar-rekap', [RekapViarController::class, 'index'])->name('viar.rekap.absen');
});

// #############################################
// Route Data Master
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master pustakawan-lihat'])->group(function () {
    Route::resource('/admin/master-pustakawan', PustakawanController::class);
    Route::get('/admin/master-detail', [PustakawanController::class, 'detail'])->name('master-pustakawan.detail');
    Route::get('/admin/master-detail_pustakawan={id}', [PustakawanController::class, 'detail'])->name('pustakawan-detail');
    Route::post('/admin/master-pustakawan/kelolah_jadwal/{id}', [PustakawanController::class, 'kelolah_pustakawan'])->name('pustakawan.kelolah_jadwal');
    Route::post('/admin/master/{id}/berkas', [PustakawanController::class, 'upBerkas'])->name('pustakawan.upBerkas');
    Route::get('/admin/master-tambah', [PustakawanController::class, 'tambah'])->name('master-tambah.pustakawan');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master pustakawan-hapus'])->group(function () {
    Route::get('/admin/master-pustakawan/{id}/hapus', [PustakawanController::class, 'destroy'])->name('master-pustakawan.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master jadwal-lihat'])->group(function () {
    Route::resource('/admin/master-jadwal', JadwalController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master jadwal-hapus'])->group(function () {
    Route::get('/admin/master-jadwal/{id}/hapus', [JadwalController::class, 'destroy'])->name('master-jadwal.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master libur-lihat'])->group(function () {
    Route::resource('/admin/master-libur', LiburController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . 'master libur-hapus'])->group(function () {
    Route::get('/admin/master-libur/{id}/hapus', [LiburController::class, 'destroy'])->name('master-libur.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master ruang-lihat'])->group(function () {
    Route::resource('/admin/master-ruang', RuangController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master ruang-hapus'])->group(function () {
    Route::get('/admin/master-ruang/{id}/hapus', [RuangController::class, 'destroy'])->name('master-ruang.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master jabatan-lihat'])->group(function () {
    Route::resource('/admin/master-jabatan', JabatanController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':master jabatan-hapus'])->group(function () {
    Route::get('/admin/master-jabatan/{id}/hapus', [JabatanController::class, 'destroy'])->name('master-jabatan.hapus');
});

// #############################################
// Route Payroll
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan kehadiran-lihat'])->group(function () {
    Route::resource('/admin/payroll-kehadiran', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan kehadiran-hapus'])->group(function () {
    Route::get('/admin/payroll-kehadiran/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-kehadiran.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan jabatan-lihat'])->group(function () {
    Route::resource('/admin/payroll-jabatan', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan jabatan-hapus'])->group(function () {
    Route::get('/admin/payroll-jabatan/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-jabatan.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan pengabdian-lihat'])->group(function () {
    Route::resource('/admin/payroll-pengabdian', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan pengabdian-hapus'])->group(function () {
    Route::get('/admin/payroll-pengabdian/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-pengabdian.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan tunkel-lihat'])->group(function () {
    Route::resource('/admin/payroll-tunkel', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan tunkel-hapus'])->group(function () {
    Route::get('/admin/payroll-tunkel/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-tunkel.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan kehormatan-lihat'])->group(function () {
    Route::resource('/admin/payroll-kehormatan', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan kehormatan-hapus'])->group(function () {
    Route::get('/admin/payroll-kehormatan/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-kehormatan.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan anak-lihat'])->group(function () {
    Route::resource('/admin/payroll-anak', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan anak-hapus'])->group(function () {
    Route::get('/admin/payroll-anak/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-anak.hapus');
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan rank-lihat'])->group(function () {
    Route::resource('/admin/payroll-rankDosen', PayrollController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':tunjangan rank-hapus'])->group(function () {
    Route::get('/admin/payroll-rankDosen/{id}/hapus', [PayrollController::class, 'destroy'])->name('payrol-rankDosen.hapus');
});

// #############################################
// Route Hak Akses Pengguna
// #############################################

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':hak pengguna-lihat'])->group(function () {
    Route::resource('/admin/pengguna', UserController::class);
    Route::post('/admin/pengguna/ubahpassword/{id}', [UserController::class, 'ubahpassword']);
    Route::get('/admin/pengguna-akses-{id}-akses', [UserController::class, 'akses']);
    Route::post('/admin/pengguna-akses/{id}/update', [UserController::class, 'updateAkses']);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':hak pengguna-hapus'])->group(function () {
    Route::get('/admin/pengguna({id})/hapus', [UserController::class, 'hapus']);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':hak akses-lihat'])->group(function () {
    Route::resource('/admin/pengguna-akses', PermissionController::class);
});

Route::middleware([Authenticate::class, PermissionMiddleware::class . ':hak akses-hapus'])->group(function () {
    Route::get('/admin/pengguna-akses/{id}/hapus', [PermissionController::class, 'destroy']);
});


// route proxy
Route::get('/api/provinces', function () {
    return Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
});

Route::get('/api/regencies/{id}', function ($id) {
    return Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$id}.json");
});

Route::get('/api/districts/{id}', function ($id) {
    return Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$id}.json");
});

Route::get('/api/villages/{id}', function ($id) {
    return Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$id}.json");
});

