<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Barang
Route::resource('barang', BarangController::class)->middleware('auth');

// Supplier — resource + fitur tambahan
Route::middleware('auth')->group(function () {
    Route::resource('supplier', SupplierController::class);
    Route::patch('supplier/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('supplier.toggle-status');
    Route::get('supplier-export/excel', [SupplierController::class, 'exportExcel'])->name('supplier.export.excel');
    Route::get('supplier-export/pdf',   [SupplierController::class, 'exportPdf'])->name('supplier.export.pdf');
});
