<?php

use App\Http\Controllers\Admin\CategoryPengaduanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanMasukController;
use App\Http\Controllers\Admin\RiwayatPengaduanController;
use App\Http\Controllers\Satgas\DashboardController as SatgasDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\LaporPerundunganController;
use App\Http\Controllers\pages\CekStatusLaporanController;
use App\Http\Controllers\pages\FaqController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\SatgasController;
use App\Http\Controllers\Satgas\MyCasesController;

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

$controller_path = 'App\Http\Controllers';

// Main Page Route

Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/lapor-perundungan', [LaporPerundunganController::class, 'index'])->name('pages-lapor-perundungan');
Route::post('/lapor-perundungan', [LaporPerundunganController::class, 'store'])->name('pages-lapor-perundungan.store');
Route::get('/cek-status-laporan', [CekStatusLaporanController::class, 'index'])->name('pages-cek-status-laporan');
Route::post('/cek-status-laporan', [CekStatusLaporanController::class, 'check'])
  ->middleware('throttle:10,1')
  ->name('pages-cek-status-laporan.check');
Route::get('/faq', [FaqController::class, 'index'])->name('pages-faq');



Route::middleware(['auth', 'role:admin'])->group(function () {
  Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
  Route::get('/admin/kategori-kasus', [CategoryPengaduanController::class, 'index'])->name('admin.kategori-kasus.index');
  Route::get('/admin/kategori-kasus/datatable', [CategoryPengaduanController::class, 'datatable'])->name('admin.kategori-kasus.datatable');
  Route::post('/admin/kategori-kasus', [CategoryPengaduanController::class, 'store'])->name('admin.kategori-kasus.store');
  Route::put('/admin/kategori-kasus/{id}', [CategoryPengaduanController::class, 'update'])->name('admin.kategori-kasus.update');
  Route::delete('/admin/kategori-kasus/{id}', [CategoryPengaduanController::class, 'destroy'])->name('admin.kategori-kasus.destroy');

  Route::get('/admin/laporan-masuk', [LaporanMasukController::class, 'index'])->name('admin.laporan-masuk.index');
  Route::get('/admin/laporan-masuk/{id}', [LaporanMasukController::class, 'show'])->name('admin.laporan-masuk.show');
  Route::post('/admin/laporan-masuk/{id}/assign', [LaporanMasukController::class, 'assign'])->name('admin.laporan-masuk.assign');
  Route::post('/admin/laporan-masuk/{id}/status', [LaporanMasukController::class, 'updateStatus'])->name('admin.laporan-masuk.status');
  Route::delete('/admin/laporan-masuk/{id}', [LaporanMasukController::class, 'destroy'])->name('admin.laporan-masuk.destroy');

  Route::get('/admin/riwayat-pengaduan', [RiwayatPengaduanController::class, 'index'])->name('admin.riwayat-pengaduan.index');
  Route::get('/admin/riwayat-pengaduan/datatable', [RiwayatPengaduanController::class, 'datatable'])->name('admin.riwayat-pengaduan.datatable');

  Route::get('/admin/faq', [AdminFaqController::class, 'index'])->name('admin.faq.index');
  Route::get('/admin/faq/datatable', [AdminFaqController::class, 'datatable'])->name('admin.faq.datatable');
  Route::post('/admin/faq', [AdminFaqController::class, 'store'])->name('admin.faq.store');
  Route::put('/admin/faq/{id}', [AdminFaqController::class, 'update'])->name('admin.faq.update');
  Route::delete('/admin/faq/{id}', [AdminFaqController::class, 'destroy'])->name('admin.faq.destroy');

  Route::get('/admin/satgas', [SatgasController::class, 'index'])->name('admin.satgas.index');
  Route::get('/admin/satgas/datatable', [SatgasController::class, 'datatable'])->name('admin.satgas.datatable');
  Route::post('/admin/satgas', [SatgasController::class, 'store'])->name('admin.satgas.store');
  Route::put('/admin/satgas/{id}', [SatgasController::class, 'update'])->name('admin.satgas.update');
  Route::delete('/admin/satgas/{id}', [SatgasController::class, 'destroy'])->name('admin.satgas.destroy');
});

Route::middleware(['auth', 'role:satgas'])->group(function () {
  Route::get('/satgas/dashboard', [SatgasDashboardController::class, 'index'])->name('satgas.dashboard.index');
  Route::get('/my-cases', [MyCasesController::class, 'index'])->name('satgas.my-cases.index');
  Route::get('/my-cases/{id}', [MyCasesController::class, 'show'])->name('satgas.my-cases.show');
  Route::post('/my-cases/{id}/log', [MyCasesController::class, 'storeLog'])->name('satgas.my-cases.store-log');
});


// authentication
Route::get('/login', $controller_path . '\authentications\LoginBasic@index')->name('login');
Route::post('/login', $controller_path . '\authentications\LoginBasic@login')->name('login.store');
Route::post('/logout', $controller_path . '\authentications\LoginBasic@logout')->name('logout');
Route::get('/logout', $controller_path . '\authentications\LoginBasic@logout')->name('logout.get');

Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::post('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@register')->name('auth-register-basic.store');
