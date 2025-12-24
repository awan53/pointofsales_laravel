<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MidtransWebhookController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 Route::post('/api/midtrans-callback', [MidtransWebhookController::class, 'handler']);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Dashboard setelah login
    
    Route::get('/home', function () {
        return view('home'); 
    })->name('home');

    // --- KHUSUS ADMINISTRATOR (User Management & Laporan) ---
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
    });

    // --- MODUL SUPPLY CHAIN (Produk & Pembelian) ---
    // Admin juga diizinkan akses ini
    Route::middleware(['role:admin,supply_chain'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('purchases', PurchaseController::class);
    });

    // --- MODUL KASIR (Penjualan) ---
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('sales', SaleController::class);
       
    });
});
// Route::resource('categories', CategoryController::class);
// Route::resource('products', ProductController::class);

// Route::resource('supplier', SupplierController::class);
// Route::resource('purchases', PurchaseController::class);
// Route::resource('sales', SaleController::class);
// Route::get('/report', [ReportController::class, 'index'])->name('reports.index');

