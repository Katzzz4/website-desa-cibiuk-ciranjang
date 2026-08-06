<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfilDesaController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\ProfilDesaController as AdminProfilDesaController;
use App\Http\Controllers\Admin\PerangkatController;
use App\Http\Controllers\Admin\InfografisController as AdminInfografisController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\PetaLaporanController;
use App\Http\Controllers\Admin\RekapLaporanController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\PotensiController;
use App\Http\Controllers\Admin\PotensiController as AdminPotensiController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TransparansiController;
use App\Http\Controllers\PencarianController;
use App\Http\Controllers\PetaWilayahController;
use App\Http\Controllers\Admin\PetaLayerController;

// ====================================================================
// HALAMAN PUBLIK
// ====================================================================
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/profil', [ProfilDesaController::class, 'index'])->name('profil.index');
Route::get('/infografis/penduduk', [InfografisController::class, 'penduduk'])->name('infografis.penduduk');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/{dokumen}/unduh', [DokumenController::class, 'unduh'])->name('dokumen.unduh');
Route::get('/dokumen/{klasifikasi}', [DokumenController::class, 'daftar'])->name('dokumen.daftar');
Route::get('/potensi', [PotensiController::class, 'index'])->name('potensi.index');
Route::get('/peta-wilayah', [PetaWilayahController::class, 'index'])->name('peta.index');
Route::get('/transparansi', [TransparansiController::class, 'index'])->name('transparansi.index');
Route::get('/cari', [PencarianController::class, 'index'])->name('pencarian.index');

Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
});

Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::get('/', [PengaduanController::class, 'create'])->name('create');
    Route::post('/', [PengaduanController::class, 'store'])->name('store');
    Route::get('/berhasil/{noTiket}', [PengaduanController::class, 'berhasil'])->name('berhasil');
    Route::get('/lacak', [PengaduanController::class, 'formLacak'])->name('lacak.form');
    Route::post('/lacak', [PengaduanController::class, 'lacak'])->name('lacak');
});

// ====================================================================
// AKUN (BAWAAN BREEZE)
// ====================================================================
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================================================================
// DASHBOARD (PERLU LOGIN + PERAN BERWENANG)
// ====================================================================
Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ------------------------------------------------------------------
        // Dapat diakses semua peran berwenang, termasuk Kepala Dusun.
        // Isinya sudah dibatasi per dusun lewat scope untukPengguna().
        //
        // CATATAN URUTAN: '/pengaduan/peta' dan '/pengaduan/rekap' HARUS
        // berada di atas '/pengaduan/{laporan}'. Bila di bawahnya, kata
        // "peta" dan "rekap" akan dibaca sebagai nomor laporan.
        // ------------------------------------------------------------------
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengaduan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/pengaduan/peta', [PetaLaporanController::class, 'index'])->name('laporan.peta');
        Route::get('/pengaduan/rekap', [RekapLaporanController::class, 'index'])->name('laporan.rekap');
        Route::get('/pengaduan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::patch('/pengaduan/{laporan}/status', [LaporanController::class, 'updateStatus'])->name('laporan.update-status');

        // ------------------------------------------------------------------
        // Pengelolaan isi situs — hanya Super Admin dan Admin Desa.
        // Kepala Dusun tidak diikutkan karena isi di bawah ini terbit
        // sebagai pernyataan resmi pemerintah desa.
        // ------------------------------------------------------------------
        Route::middleware(\App\Http\Middleware\EnsureIsAdminDesa::class)->group(function () {

            Route::resource('berita', AdminBeritaController::class)
                ->except(['show'])
                ->parameters(['berita' => 'berita']);

            Route::get('/profil', [AdminProfilDesaController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [AdminProfilDesaController::class, 'update'])->name('profil.update');

            Route::resource('perangkat', PerangkatController::class)->except(['show']);

            Route::get('/infografis/ringkasan', [AdminInfografisController::class, 'editRingkasan'])->name('infografis.ringkasan');
            Route::put('/infografis/ringkasan', [AdminInfografisController::class, 'updateRingkasan'])->name('infografis.ringkasan.update');

            Route::prefix('infografis/kategori')->name('infografis.kategori.')->group(function () {
                Route::get('/', [AdminInfografisController::class, 'kategoriIndex'])->name('index');
                Route::get('/create', [AdminInfografisController::class, 'kategoriCreate'])->name('create');
                Route::post('/', [AdminInfografisController::class, 'kategoriStore'])->name('store');
                Route::get('/{kategori}/edit', [AdminInfografisController::class, 'kategoriEdit'])->name('edit');
                Route::put('/{kategori}', [AdminInfografisController::class, 'kategoriUpdate'])->name('update');
                Route::delete('/{kategori}', [AdminInfografisController::class, 'kategoriDestroy'])->name('destroy');
            });

            Route::prefix('infografis/data')->name('infografis.data.')->group(function () {
                Route::get('/', [AdminInfografisController::class, 'dataIndex'])->name('index');
                Route::get('/create', [AdminInfografisController::class, 'dataCreate'])->name('create');
                Route::post('/', [AdminInfografisController::class, 'dataStore'])->name('store');
                Route::get('/{datum}/edit', [AdminInfografisController::class, 'dataEdit'])->name('edit');
                Route::put('/{datum}', [AdminInfografisController::class, 'dataUpdate'])->name('update');
                Route::delete('/{datum}', [AdminInfografisController::class, 'dataDestroy'])->name('destroy');
            });

            Route::resource('galeri', AdminGaleriController::class)->except(['show']);

            Route::resource('agenda', AdminAgendaController::class)
                ->except(['show'])
                ->parameters(['agenda' => 'agenda']);

            Route::resource('dokumen', AdminDokumenController::class)
                ->except(['show'])
                ->parameters(['dokumen' => 'dokumen']);

            Route::resource('potensi', AdminPotensiController::class)
                ->except(['show'])
                ->parameters(['potensi' => 'potensi']);

            Route::resource('peta-layer', PetaLayerController::class)
                ->except(['show'])
                ->parameters(['peta-layer' => 'peta_layer']);

        }); // akhir pengelolaan isi situs

        // ------------------------------------------------------------------
        // Pengelolaan akun — hanya Super Admin
        // ------------------------------------------------------------------
        Route::middleware(\App\Http\Middleware\EnsureIsSuperadmin::class)->group(function () {
            Route::resource('user', UserController::class)->except(['show']);
        });
    });

require __DIR__ . '/auth.php';