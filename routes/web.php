<?php


use App\Http\Controllers\Absen\AbsenController;
use App\Http\Controllers\Absen\AbsenTenagaKhidmahController;
use App\Http\Controllers\Absen\AbsenViarController;
use App\Http\Controllers\Absen\WebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\JadwalController;
use App\Http\Controllers\Master\LiburController;
use App\Http\Controllers\Master\PendidikanPagiController;
use App\Http\Controllers\Master\PustakawanController;
use App\Http\Controllers\Master\RuangController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Struktural\BarokahStrukturalController;
use App\Http\Controllers\Struktural\IzinStrukturalController;
use App\Http\Controllers\Struktural\LaporanStrukturalController;
use App\Http\Controllers\Struktural\RekapStrukturalController;
use App\Http\Controllers\Struktural\WhatsAppController;
use App\Http\Controllers\TenagaKhidmah\BarokahTenagaKhidmahController;
use App\Http\Controllers\TenagaKhidmah\IzinTenagaKhidmahController; // baru izin
use App\Http\Controllers\TenagaKhidmah\LaporanTenagaKhidmahController;
use App\Http\Controllers\Viar\LaporanViarController;
use App\Http\Controllers\TenagaKhidmah\RekapTenagaKhidmahController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Viar\BarokahViarController;
use App\Http\Controllers\Viar\IzinViarController;
use App\Http\Controllers\Viar\RekapViarController;
use App\Http\Controllers\Absen\WaNotifController;
use App\Http\Middleware\PermissionMiddleware;
use App\Models\Absen\AbsenViar;
use App\Models\Master\Pustakawan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


// Area Admin
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/halo', function () {
    return "Routing Laravel Berhasil Jalan!";
});
// Middleware hanya untuk pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('home-user');
});


// Middleware hanya untuk pengguna dengan izin "manage users"
Route::resource('/admin/pengguna', UserController::class);

Route::middleware(['auth', PermissionMiddleware::class . ':pengguna-ubah password'])->group(function () {
    Route::post('/admin/pengguna/ubahpassword({id})', [UserController::class, 'ubahpassword']);
});

Route::middleware(['auth', PermissionMiddleware::class . ':pengguna-hapus'])->group(function () {
    Route::get('/admin/pengguna({id})/hapus', [UserController::class, 'hapus']);
});

Route::middleware(['auth', PermissionMiddleware::class . ':pengguna-akses pengguna'])->group(function () {
    Route::get('/admin/pengguna-akses/{id}/akses', [UserController::class, 'akses']);
    Route::post('/admin/pengguna-akses/{id}/update', [UserController::class, 'updateAkses']);
});

Route::middleware(['auth', PermissionMiddleware::class . ':akses pengguna-lihat'])->group(function () {
    Route::resource('/admin/pengguna-akses', PermissionController::class);
});

Route::middleware(['auth', PermissionMiddleware::class . ':akses pengguna-hapus'])->group(function () {
    Route::get('/admin/pengguna-akses/{id}/hapus', [PermissionController::class, 'destroy']);
});

// geofencing
Route::resource('/admin/geofencing', WaNotifController::class);

Route::match(['get', 'post'], '/webhook-fonnte', [WaNotifController::class, 'webhook'])->name('webhook.fonnte');

Route::get('/admin/absen-ruang', [AbsenController::class, 'index'])->name('absen.ruang');
Route::get('/absen-struktural', [AbsenController::class, 'struktural'])->name('absen-struktural');
Route::post('/absen-struktural', [AbsenController::class, 'absen_struktural'])->name('struktural-proses');
Route::get('/admin/absen-struktural_mandiri', [AbsenController::class, 'mandiri'])->name('struktural-mandiri');
Route::post('/admin/absen-struktural_mandiri', [AbsenController::class, 'proses_mandiri'])->name('struktural-mandiri.store');
Route::get('/admin/absen-struktural/{id}/hapus', [AbsenController::class, 'destroy'])->name('struktural-absen.hapus');

// #############################################
// tempat untuk absen tenaga khidmah dan viar
// #############################################
Route::middleware(['auth'])->group(function () {
Route::get('/absen-khidmah', [AbsenController::class, 'khidmah'])->name('absen-khidmah');
Route::post('/absen-khidmah', [AbsenController::class, 'absen_khidmah'])->name('khidmah-proses');

// Route::get('/admin/absen-viar', [AbsenController::class, 'viar'])->name('absen-viar');
// Route::post('/admin/absen-viar', [AbsenController::class, 'absen_viar'])->name('viar-proses');

// Absen Tenaga Khidmah Baru start::
Route::get('/absen-khidmah-room', [AbsenTenagaKhidmahController::class, 'khidmah'])->name('absen-khidmah-room');
Route::post('/absen-khidmah-room', [AbsenTenagaKhidmahController::class, 'absen_khidmah'])->name('khidmah-proses-room');
Route::get('/admin/absen-khidmah_mandiri', [AbsenTenagaKhidmahController::class, 'mandiri'])->name('khidmah-mandiri');
Route::post('/admin/absen-khidmah_mandiri', [AbsenTenagaKhidmahController::class, 'proses_mandiri'])->name('khidmah-mandiri.store');
Route::get('/admin/absen-khidmah/{id}/hapus', [AbsenTenagaKhidmahController::class, 'destroy'])->name('khidmah.absen.hapus');// uji coba absen dummy End::

// Absen Tenaga Viar Baru Start::
Route::get('/absen-viar-room', [AbsenViarController::class, 'viar'])->name('absen-viar-dummy');
Route::post('/absen-viar-room', [AbsenViarController::class, 'absen_viar'])->name('absen-viar-proses');
Route::get('/admin/absen-viar_mandiri', [AbsenViarController::class, 'mandiri'])->name('viar-mandiri');
Route::post('/admin/absen-viar_mandiri', [AbsenViarController::class, 'proses_mandiri'])->name('viar-mandiri.store');
Route::get('/admin/absen-viar/{id}/hapus', [AbsenViarController::class, 'destroy'])->name('viar.absen.hapus');// uji coba absen dummy End::
// Absen Tenaga Viar Baru End::

});


// Setting Koordinat
Route::get('/admin/struktural-geofencing', [WaNotifController::class, 'index']);
Route::get('/admin/struktural-create_geofencing', [WaNotifController::class, 'create_geofencing'])->name('geofencing.tambah');
Route::get('/admin/struktural-update_geofencing={id}', [WaNotifController::class, 'update_geofencing'])->name('update-geofencing.lihat');
Route::post('/admin/struktural-geofencing', [WaNotifController::class, 'store']);
Route::put('/admin/struktural-geofencing/{id}', [WaNotifController::class, 'update'])->name('koordinat-lokasi.update');
Route::get('/admin/struktural-geofencing/{id}', [WaNotifController::class, 'destroy'])->name('geofencing.hapus');

// Izin Struktural
Route::resource('/admin/struktural-izin', IzinStrukturalController::class);
Route::get('/admin/struktural-izin/{id}/hapus', [IzinStrukturalController::class, 'destroy'])->name('struktural-izin.hapus');

// Izin Pustakawan Uji Coba Start::
Route::get('/admin/khidmah-izin', [IzinTenagaKhidmahController::class, 'index'])->name('khidmah.izin.index');
Route::post('/admin/khidmah-izin', [IzinTenagaKhidmahController::class, 'store'])->name('khidmah.izin.store');
Route::get('/admin/khidmah-izin/{id}', [IzinTenagaKhidmahController::class, 'destroy'])->name('khidmah.izin.destroy');

// Izin Viar Uji Coba Start::
Route::get('/admin/viar-izin', [IzinViarController::class, 'index'])->name('viar.izin.index');
Route::post('/admin/viar-store', [IzinViarController::class, 'store'])->name('viar.izin.store');
Route::get('/admin/viar/{id}/delete', [IzinViarController::class, 'destroy'])->name('viar.izin.destroy');


// Barokah Sturktural
Route::get('/admin/struktural-barokah', [BarokahStrukturalController::class, 'index'])->name('barokah-struktural');
Route::get('/admin/struktural-generate', [BarokahStrukturalController::class, 'generate'])->name('struktural-generate');
Route::post('/admin/struktural-generate', [BarokahStrukturalController::class, 'store'])->name('barokah_struktural.store');


// Barokah Pustakawan  Start::
Route::get('/admin/khidmah-barokah', [BarokahTenagaKhidmahController::class, 'index'])->name('khidmah.barokah.index');
Route::post('/admin/barokah-tambah', [BarokahTenagaKhidmahController::class, 'store'])->name('khidmah.barokah.store');
Route::put('/admin/barokah-update-khidmah/{id}', [BarokahTenagaKhidmahController::class, 'update'])->name('khidmah.barokah.update');
Route::get('/admin/barokah-destroy/{id}', [BarokahTenagaKhidmahController::class, 'destroy'])->name('khidmah.barokah.destroy');


// Barokah Viar
Route::get('/admin/viar-barokah', [BarokahViarController::class, 'index'])->name('viar.barokah.index');
Route::post('/admin/barokah-viar-tambah', [BarokahViarController::class, 'store'])->name('viar.barokah.store');
Route::put('/admin/barokah-update-viar/{id}', [BarokahViarController::class, 'update'])->name('viar.barokah.update');
Route::get('/admin/barokah-viar-destroy/{id}', [BarokahViarController::class, 'destroy'])->name('viar.barokah.destroy');



// Rekap Struktural
Route::get('/admin/struktural-rekap', [RekapStrukturalController::class, 'index'])->name('rekap.struktural');
Route::get('/admin/struktural-cetak/{bulan}/{tahun}', [LaporanStrukturalController::class, 'strukturalPDF'])->name('struktural.cetak');

// Rekap Pustakawan Uji Coba Start::
Route::get('/admin/khidmah-rekap', [RekapTenagaKhidmahController::class, 'index'])->name('rekap.tenaga_khidmah');
// Jalur khusus untuk mengunduh/melihat laporan PDF Tenaga Khidmah
Route::get('/admin/tenaga-khidmah/cetak', [LaporanTenagaKhidmahController::class, 'tenagaKhidmahPDF'])->name('tenaga-khidmah.cetak');

// Rekap Pustakawan Uji Coba Start::
Route::get('/admin/viar-rekap', [RekapViarController::class, 'index'])->name('viar.rekap.absen');
// Jalur khusus untuk mengunduh/melihat laporan PDF Tenaga Khidmah
Route::get('/admin/tenaga-viar/cetak', [LaporanViarController::class, 'viarPdf'])->name('viar.cetak');


// Kirim Laporan
Route::get('/kirim-laporan-struktural', [AbsenController::class, 'kirimLaporan'])->name('kirim.laporan.struktural');


// Data Master
// Pustakawan
Route::middleware(['auth', PermissionMiddleware::class . ':master pustakawan-lihat'])->group(function () {
    Route::resource('/admin/master-pustakawan', PustakawanController::class);
    Route::get('/admin/master-detail', [PustakawanController::class, 'detail'])->name('master-pustakawan.detail');
});

Route::middleware(['auth', PermissionMiddleware::class . ':master pustakawan-detail'])->group(function () {
    Route::get('/admin/master-detail_pustakawan={id}', [PustakawanController::class, 'detail'])->name('pustakawan-detail');
    Route::post('/admin/master-pustakawan/kelolah_jadwal/{id}', [PustakawanController::class, 'kelolah_pustakawan'])->name('pustakawan.kelolah_jadwal');
    Route::post('/admin/master/{id}/berkas', [PustakawanController::class, 'upBerkas'])->name('pustakawan.upBerkas');
});

Route::middleware(['auth', PermissionMiddleware::class . ':master pustakawan-tambah'])->group(function () {
    Route::get('/admin/master-tambah', [PustakawanController::class, 'tambah'])->name('master-tambah.pustakawan');
});

Route::get('/admin/master-pustakawan/{id}/hapus', [PustakawanController::class, 'destroy'])->name('master-pustakawan.hapus');

// Jadwal
Route::middleware(['auth', PermissionMiddleware::class . ':master jadwal-lihat'])->group(function () {
    Route::resource('/admin/master-jadwal', JadwalController::class);
});

Route::middleware(['auth', PermissionMiddleware::class . ':master jadwal-hapus'])->group(function () {
    Route::get('/admin/master-jadwal/{id}/hapus', [JadwalController::class, 'destroy'])->name('master-jadwal.hapus');
});

// Libur
Route::middleware(['auth', PermissionMiddleware::class . ':master libur-lihat'])->group(function () {
    Route::resource('/admin/master-libur', LiburController::class);
});

Route::middleware(['auth', PermissionMiddleware::class . ':master libur-hapus'])->group(function () {
    Route::get('/admin/master-libur/{id}/hapus', [LiburController::class, 'destroy'])->name('master-libur.hapus');
});

// Ruang
Route::middleware(['auth', PermissionMiddleware::class . ':master ruang-lihat'])->group(function () {
    Route::resource('/admin/master-ruang', RuangController::class);
});

Route::middleware(['auth', PermissionMiddleware::class . ':master ruang-hapus'])->group(function () {
    Route::get('/admin/master-ruang/{id}/hapus', [RuangController::class, 'destroy'])->name('master-ruang.hapus');
});

// Jabatan
Route::middleware(['auth', PermissionMiddleware::class . ':master jabatan-lihat'])->group(function () {
    Route::resource('/admin/master-jabatan', JabatanController::class);
});

Route::middleware(['auth', PermissionMiddleware::class . ':master jabatan-hapus'])->group(function () {
    Route::get('/admin/master-jabatan/{id}/hapus', [JabatanController::class, 'destroy'])->name('master-jabatan.hapus');
});

// Pendidikan Pagi
Route::middleware(['auth', PermissionMiddleware::class . ':master pendpagi-lihat'])->group(function () {
    Route::resource('/admin/master-pendpagi', PendidikanPagiController::class);
});

// Payroll
Route::middleware(['auth', PermissionMiddleware::class . ':payroll tunjangan-lihat'])->group(function () {
    // Create and Update
    Route::resource('/admin/payroll-kehadiran', PayrollController::class);
    Route::resource('/admin/payroll-jabatan', PayrollController::class);
    Route::resource('/admin/payroll-pengabdian', PayrollController::class);
    Route::resource('/admin/payroll-tunkel', PayrollController::class);
    Route::resource('/admin/payroll-kehormatan', PayrollController::class);
    Route::resource('/admin/payroll-anak', PayrollController::class);
    Route::resource('/admin/payroll-rankDosen', PayrollController::class);

    // Delete
    Route::get('/admin/payroll-kehadiran/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-kehadiran.hapus');
    Route::get('/admin/payroll-jabatan/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-jabatan.hapus');
    Route::get('/admin/payroll-pengabdian/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-pengabdian.hapus');
    Route::get('/admin/payroll-tunkel/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-tunkel.hapus');
    Route::get('/admin/payroll-kehormatan/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-kehormatan.hapus');
    Route::get('/admin/payroll-anak/{id}/hapus', [PayrollController::class, 'destroy'])->name('payroll-anak.hapus');
    Route::get('/admin/payroll-rankDosen/{id}/hapus', [PayrollController::class, 'destroy'])->name('payrol-rankDosen.hapus');
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

