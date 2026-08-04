<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// ============================================
// AUTH ROUTES
// ============================================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    
    Route::resource('bahan', App\Http\Controllers\Admin\MasterBahanController::class)->names([
        'index' => 'admin.bahan.index',
        'create' => 'admin.bahan.create',
        'store' => 'admin.bahan.store',
        'edit' => 'admin.bahan.edit',
        'update' => 'admin.bahan.update',
        'destroy' => 'admin.bahan.destroy',
    ]);
    // Admin History - semua transaksi
    Route::get('/history', [App\Http\Controllers\Admin\AdminHistoryController::class, 'index'])->name('admin.history.index');
    Route::get('/history/export-pdf', [App\Http\Controllers\Admin\AdminHistoryController::class, 'exportPdf'])->name('admin.history.export-pdf');
    // Admin History CSV
Route::get('/admin/history/export-csv', [App\Http\Controllers\Admin\AdminHistoryController::class, 'exportCsv'])->name('admin.history.export-csv')->middleware(['auth', 'role:admin']);

});

// ============================================
// PRODUKSI ROUTES - [DIPERBAIKI]
// ============================================
Route::prefix('produksi')->middleware(['auth', 'role:produksi'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Produksi\ProduksiDashboardController::class, 'index'])->name('produksi.dashboard');
    Route::get('/permintaan/create', [App\Http\Controllers\Produksi\PermintaanProduksiController::class, 'create'])->name('produksi.permintaan.create');
    Route::post('/permintaan', [App\Http\Controllers\Produksi\PermintaanProduksiController::class, 'store'])->name('produksi.permintaan.store');
    // [BARU] History permintaan produksi sendiri
    Route::get('/history', [App\Http\Controllers\Produksi\ProduksiHistoryController::class, 'index'])->name('produksi.history.index');
});

// ============================================
// GUDANG ROUTES - [DIPERBAIKI & DITAMBAH]
// ============================================
Route::prefix('gudang')->middleware(['auth', 'role:gudang'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Gudang\GudangDashboardController::class, 'index'])->name('gudang.dashboard');
    
    // [BARU] Monitoring Stok & Batch
    Route::get('/stok', [App\Http\Controllers\Gudang\StokController::class, 'index'])->name('gudang.stok.index');
    Route::get('/stok/batch/{id}', [App\Http\Controllers\Gudang\StokController::class, 'detailBatch'])->name('gudang.stok.batch');
    
    // [DIPERBAIKI] Permintaan Produksi Masuk (dari Produksi)
    Route::get('/permintaan-produksi', [App\Http\Controllers\Gudang\PermintaanProduksiGudangController::class, 'index'])->name('gudang.permintaan-produksi.index');
    Route::get('/permintaan-produksi/proses/{id}', [App\Http\Controllers\Gudang\PermintaanProduksiGudangController::class, 'proses'])->name('gudang.permintaan-produksi.proses');
    Route::post('/permintaan-produksi/proses-fifo/{id}', [App\Http\Controllers\Gudang\PermintaanProduksiGudangController::class, 'prosesFIFO'])->name('gudang.permintaan-produksi.proses-fifo');
    Route::post('/permintaan-produksi/tolak/{id}', [App\Http\Controllers\Gudang\PermintaanProduksiGudangController::class, 'tolak'])->name('gudang.permintaan-produksi.tolak');
    
    // [BARU] History Pengeluaran Barang (yang sudah diproses)
    Route::get('/pengeluaran-history', [App\Http\Controllers\Gudang\PengeluaranHistoryController::class, 'index'])->name('gudang.pengeluaran-history.index');
    
    // [DIPERBAIKI] Buat Permintaan Pengadaan
    Route::get('/permintaan-pengadaan/create', [App\Http\Controllers\Gudang\PermintaanPengadaanController::class, 'create'])->name('gudang.permintaan-pengadaan.create');
    Route::post('/permintaan-pengadaan', [App\Http\Controllers\Gudang\PermintaanPengadaanController::class, 'store'])->name('gudang.permintaan-pengadaan.store');
    
    // [BARU] History Permintaan Pengadaan (yang dibuat)
    Route::get('/permintaan-pengadaan-history', [App\Http\Controllers\Gudang\PermintaanPengadaanHistoryController::class, 'index'])->name('gudang.permintaan-pengadaan-history.index');
    
    // [DIPERBAIKI] Barang Masuk (verifikasi)
    Route::get('/barang-masuk', [App\Http\Controllers\Gudang\BarangMasukGudangController::class, 'index'])->name('gudang.barang-masuk.index');
    Route::get('/barang-masuk/verifikasi/{id}', [App\Http\Controllers\Gudang\BarangMasukGudangController::class, 'verifikasi'])->name('gudang.barang-masuk.verifikasi');
    Route::post('/barang-masuk/konfirmasi/{id}', [App\Http\Controllers\Gudang\BarangMasukGudangController::class, 'konfirmasi'])->name('gudang.barang-masuk.konfirmasi');
    Route::post('/barang-masuk/tolak/{id}', [App\Http\Controllers\Gudang\BarangMasukGudangController::class, 'tolak'])->name('gudang.barang-masuk.tolak');
    
    // [BARU] History Barang Masuk (yang diverifikasi)
    Route::get('/barang-masuk-history', [App\Http\Controllers\Gudang\BarangMasukHistoryController::class, 'index'])->name('gudang.barang-masuk-history.index');
});

// ============================================
// PENGADAAN ROUTES - [DIPERBAIKI & DITAMBAH]
// ============================================
Route::prefix('pengadaan')->middleware(['auth', 'role:pengadaan'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Pengadaan\PengadaanDashboardController::class, 'index'])->name('pengadaan.dashboard');
    
    // [DIPERBAIKI] Permintaan Pengadaan Masuk (dari Gudang)
    Route::get('/permintaan', [App\Http\Controllers\Pengadaan\PermintaanGudangController::class, 'index'])->name('pengadaan.permintaan.index');
    Route::get('/permintaan/proses/{id}', [App\Http\Controllers\Pengadaan\PermintaanGudangController::class, 'proses'])->name('pengadaan.permintaan.proses');
    
    // [BARU] History Permintaan Pengadaan (yang diproses)
    Route::get('/permintaan-history', [App\Http\Controllers\Pengadaan\PermintaanPengadaanHistoryController::class, 'index'])->name('pengadaan.permintaan-history.index');
    
    // [DIPERBAIKI] Input Barang Masuk
    Route::get('/barang-masuk/create/{id}', [App\Http\Controllers\Pengadaan\BarangMasukController::class, 'create'])->name('pengadaan.barang-masuk.create');
    Route::post('/barang-masuk/store/{id}', [App\Http\Controllers\Pengadaan\BarangMasukController::class, 'store'])->name('pengadaan.barang-masuk.store');
    
    // [BARU] History Input Barang (yang diinput)
    Route::get('/barang-masuk-history', [App\Http\Controllers\Pengadaan\BarangMasukHistoryController::class, 'index'])->name('pengadaan.barang-masuk-history.index');
});

// ============================================
// AJAX NOTIFICATION
// ============================================
Route::get('/ajax/notifikasi', [App\Http\Controllers\NotificationController::class, 'getNotifikasi'])->name('ajax.notifikasi')->middleware(['auth']);

// ============================================
// EXPORT PDF ROUTES
// ============================================

// Admin History PDF
Route::get('/admin/history/export-pdf', [App\Http\Controllers\Admin\AdminHistoryController::class, 'exportPdf'])->name('admin.history.export-pdf')->middleware(['auth', 'role:admin']);

// Produksi History PDF
Route::get('/produksi/history/export-pdf', [App\Http\Controllers\Produksi\ProduksiHistoryController::class, 'exportPdf'])->name('produksi.history.export-pdf')->middleware(['auth', 'role:produksi']);

// Gudang - Pengeluaran History PDF
Route::get('/gudang/pengeluaran-history/export-pdf', [App\Http\Controllers\Gudang\PengeluaranHistoryController::class, 'exportPdf'])->name('gudang.pengeluaran-history.export-pdf')->middleware(['auth', 'role:gudang']);

// Gudang - Permintaan Pengadaan History PDF
Route::get('/gudang/permintaan-pengadaan-history/export-pdf', [App\Http\Controllers\Gudang\PermintaanPengadaanHistoryController::class, 'exportPdf'])->name('gudang.permintaan-pengadaan-history.export-pdf')->middleware(['auth', 'role:gudang']);

// Gudang - Barang Masuk History PDF
Route::get('/gudang/barang-masuk-history/export-pdf', [App\Http\Controllers\Gudang\BarangMasukHistoryController::class, 'exportPdf'])->name('gudang.barang-masuk-history.export-pdf')->middleware(['auth', 'role:gudang']);

// Pengadaan - Permintaan History PDF
Route::get('/pengadaan/permintaan-history/export-pdf', [App\Http\Controllers\Pengadaan\PermintaanPengadaanHistoryController::class, 'exportPdf'])->name('pengadaan.permintaan-history.export-pdf')->middleware(['auth', 'role:pengadaan']);

// Pengadaan - Barang Masuk History PDF
Route::get('/pengadaan/barang-masuk-history/export-pdf', [App\Http\Controllers\Pengadaan\BarangMasukHistoryController::class, 'exportPdf'])->name('pengadaan.barang-masuk-history.export-pdf')->middleware(['auth', 'role:pengadaan']);