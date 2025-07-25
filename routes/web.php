<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes (tanpa middleware auth)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MonitoringAkademikController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\MonitoringPelanggaranController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\TahunAkadikController;
use Illuminate\Support\Facades\Storage;

Route::get('password/reset', function () {
    return view('auth.forgot-password');
})->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Common routes accessible to all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('user.home');
    })->name('home');
});
Route::middleware(['auth'])->get('/rapor/cetak/{detailId}', [NilaiController::class, 'cetak'])
    ->name('rapor.cetak');
// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/filter-data', [AdminController::class, 'getFilterData']);
    Route::get('/admin/mapel-data', [AdminController::class, 'getMapelData']);
    Route::get('/admin/data-pelanggaran/{tahun}', [AdminController::class, 'getPelanggaranData']);

    Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    // Route Sekolah
    Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah.index');
    Route::get('/sekolah-create', [SekolahController::class, 'create'])->name('sekolah.create');
    Route::post('/sekolah/store', [SekolahController::class, 'store'])->name('sekolah.store');
    Route::get('/sekolah-edit/{id}', [SekolahController::class, 'edit'])->name('sekolah.edit');
    Route::put('/sekolah-update/{id}', [SekolahController::class, 'update'])->name('sekolah.update');
    Route::delete('/sekolah-delete/{id}', [SekolahController::class, 'destroy'])->name('sekolah.destroy');
    // Route Kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas-update/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas-delete/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    // Route Mapel
    Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index');
    Route::post('/mapel/store', [MapelController::class, 'store'])->name('mapel.store');
    Route::put('/mapel-update/{id}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel-delete/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');
    // Route Guru
    Route::get('/guru/sekolah/{id}', [GuruController::class, 'bySekolah'])->name('guru.bySekolah');
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::post('/guru/store', [GuruController::class, 'store'])->name('guru.store');
    Route::put('/guru-update/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{nis}', [GuruController::class, 'destroy'])->name('guru.destroy');
    // Route Tahun Akademik
    Route::get('/akademik', [TahunAkadikController::class, 'index'])->name('akademik.index');
    Route::get('/akademik-create', [TahunAkadikController::class, 'create'])->name('akademik.create');
    Route::post('/akademik/store', [TahunAkadikController::class, 'store'])->name('akademik.store');
    Route::get('/akademik-edit/{id}', [TahunAkadikController::class, 'edit'])->name('akademik.edit');
    Route::put('/akademik-update/{id}', [TahunAkadikController::class, 'update'])->name('akademik.update');
    Route::delete('/akademik/{id}', [TahunAkadikController::class, 'destroy'])->name('akademik.destroy');
    Route::get('/tanggal-cetak', [TahunAkadikController::class, 'editTgl'])->name('tanggal-cetak');
    Route::put('/tanggal-cetak/update', [TahunAkadikController::class, 'updateTgl'])->name('tanggal-cetak.update');

    Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
    Route::get('/santri/download-template', [SantriController::class, 'downloadTemplate'])->name('santri.template');
    Route::get('/santri/{nis}', [SantriController::class, 'show'])->name('santri.show');
    Route::post('/santri/store', [SantriController::class, 'store'])->name('santri.store');
    Route::put('/santri/{nis}', [SantriController::class, 'update'])->name('santri.update');
    Route::delete('/santri/{nis}', [SantriController::class, 'destroy'])->name('santri.destroy');
    Route::post('/santri/import', [SantriController::class, 'importExcel'])->name('santri.import');


    // Halaman utama nilai
    Route::get('/nilai-all', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/kelas', [NilaiController::class, 'getKelas']);
    Route::get('/nilai/santri', [NilaiController::class, 'getSantri']);
    Route::get('/nilai/detail/{detailId}', [NilaiController::class, 'getDetail']);
    // Route::get('/rapor/cetak/{id}', [NilaiController::class, 'cetak']);
    // Route
    Route::get('/rapor/cetak-semua', [NilaiController::class, 'cetakSemua']);
    // Route::get('/grafik', [AdminController::class, 'grafik'])->name('admin.grafik');


    // Route Rombongan Belajar (Pembagian Kelas Santri)
    Route::get('/rombongan-belajar', [RombelController::class, 'index'])->name('rombel.index');
    Route::get('/rombongan-belajar/create', [RombelController::class, 'create'])->name('rombel.create');
    Route::post('/rombongan-belajar/store', [RombelController::class, 'store'])->name('rombel.store');
    Route::delete('/rombongan-belajar/hapus-santri', [RombelController::class, 'hapusSantri'])->name('rombel.hapusSantri');
    Route::post('/rombongan-belajar/naik-kelas', [RombelController::class, 'naikKelas'])->name('rombel.naikKelas');
    Route::get('/rombongan-belajar/santri/{detail}', [RombelController::class, 'getSantriRombel']);

    // Route Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/tahun/{id}', [JadwalController::class, 'getByTahun']);
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');


    // Route khusus untuk verifikasi surat
    Route::get('/pelanggaran/{id}/verifikasi', [PelanggaranController::class, 'verifikasiForm'])->name('pelanggaran.verifikasi.form');
    Route::post('/pelanggaran/{id}/verifikasi', [PelanggaranController::class, 'verifikasiSubmit'])->name('pelanggaran.verifikasi.submit');

    Route::get('/monitoring-akademik', [MonitoringController::class, 'index'])->name('monitoring.akademik');
    Route::get('/monitoring/akademik/{santri}', [MonitoringController::class, 'show'])->name('monitoring.akademik.show');
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/nilai', [NilaiController::class, 'daftarJadwalGuru'])->name('nilai.dashboard');
    Route::post('/nilai/store', [NilaiController::class, 'store'])->name('nilai.store');
    // opsional
    Route::get('/nilai/input/{jadwal}', [NilaiController::class, 'input'])->name('nilai.input');
    Route::post('/pelanggaran/{id}/verifikasi', [PelanggaranController::class, 'verifikasiSubmit'])->name('pelanggaran.verifikasi.submit');
});

// Wali Santri routes
Route::middleware(['auth', 'role:wali_santri'])->group(function () {

    // Dashboard grafik
    Route::get('/grafik/santri', [SantriController::class, 'pelanggaranSantri'])
        ->name('grafik.santri');

    // Profil santri
    Route::get('/wali/santri', [SantriController::class, 'waliSantri'])
        ->name('wali.santri');

    // Nilai santri
    Route::get('/wali/nilai', [NilaiController::class, 'nilaiWali'])
        ->name('wali.nilai');

    // Pelanggaran santri
    Route::get('/wali/pelanggaran', [PelanggaranController::class, 'pelanggaranWali'])
        ->name('wali.pelanggaran');

    // Ubah password (hanya password)
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
});

Route::middleware(['auth', 'role:admin|kesiswaan'])->group(function () {

    Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
    Route::put('/pelanggaran/{id}', [PelanggaranController::class, 'update'])->name('pelanggaran.update');
    Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

    //Route Jenis Pelanggaran
    Route::get('/jenis', [JenisPelanggaranController::class, 'index'])->name('jenis.index');
    Route::post('/jenis/store', [JenisPelanggaranController::class, 'store'])->name('jenis.store');
    Route::put('/jenis-update/{id}', [JenisPelanggaranController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis-delete/{id}', [JenisPelanggaranController::class, 'destroy'])->name('jenis.destroy');
});

Route::middleware(['auth', 'role:admin|kesiswaan|guru'])->group(function () {
    Route::get('/dashboard-kesiswaan', [PelanggaranController::class, 'indexKesiswaan'])->name('kesiswaan.dashboard');
    Route::get('/kesiswaan/chart/{tahun}', [PelanggaranController::class, 'chartData'])
        ->name('kesiswaan.chart');
    Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
    Route::get('/download-template', [PelanggaranController::class, 'downloadTemplateSurat'])->name('pelanggaran.template');
    Route::get('/jenis-pelanggaran', [JenisPelanggaranController::class, 'index'])->name('jenis.pelanggaran');
    Route::get('/laporan-pelanggaran', [PelanggaranController::class, 'laporanPelanggaran'])
        ->name('laporan.pelanggaran');
    Route::get('/laporan-pelanggaran/excel', [PelanggaranController::class, 'exportExcel'])
        ->name('laporan.pelanggaran.excel');
    Route::get('/monitoring-akademik', [MonitoringAkademikController::class, 'index'])
        ->name('monitoring.akademik');
    Route::get('/monitoring-akademik/data', [MonitoringAkademikController::class, 'fetch'])
        ->name('monitoring.akademik.data');
    Route::get('/monitoring-akademik/export', [MonitoringAkademikController::class, 'exportExcel'])
        ->name('monitoring.akademik.export');
    Route::get('/monitoring-pelanggaran', [MonitoringPelanggaranController::class, 'index'])
        ->name('monitoring.pelanggaran');
    Route::get('/monitoring-pelanggaran/{santri}', [MonitoringPelanggaranController::class, 'show'])
        ->name('monitoring.pelanggaran.show');
    Route::get('/pelanggaran/download-template', [PelanggaranController::class, 'downloadTemplate'])
        ->name('pelanggaran.download.template');
    // tambahkan lainnya jika perlu
});
// // Route yang butuh login
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     // Tambahkan route lainmu di sini...
// });
Route::post('fonnte', [PelanggaranController::class, 'sendNotif'])->name('notif');
