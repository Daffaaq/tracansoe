<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlusServiceController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::prefix('dashboard')->group(function () {
        Route::get('/promosi', [PromosiController::class, 'index'])->name('promosi.index');
        Route::get('/promosi/create', [PromosiController::class, 'create'])->name('promosi.create');
        Route::post('/promosi/store', [PromosiController::class, 'store'])->name('promosi.store');
        Route::get('/promosi/show/{uuid}', [PromosiController::class, 'show'])->name('promosi.show');
        Route::get('/promosi/edit/{uuid}', [PromosiController::class, 'edit'])->name('promosi.edit');
        Route::put('/promosi/update/{uuid}', [PromosiController::class, 'update'])->name('promosi.update');
        Route::delete('/promosi/delete/{uuid}', [PromosiController::class, 'destroy'])->name('promosi.destroy');
        Route::post('/promosi/list', [PromosiController::class, 'list'])->name('promosi.list');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [CategoryController::class, 'create'])->name('kategori.create');
        Route::post('/kategori/store', [CategoryController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/show/{uuid}', [CategoryController::class, 'show'])->name('kategori.show');
        Route::get('/kategori/edit/{uuid}', [CategoryController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/update/{uuid}', [CategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/delete/{uuid}', [CategoryController::class, 'destroy'])->name('kategori.destroy');
        Route::post('/kategori/list', [CategoryController::class, 'list'])->name('kategori.list');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/plus-service', [PlusServiceController::class, 'index'])->name('plus-service.index');
        Route::get('/plus-service/create', [PlusServiceController::class, 'create'])->name('plus-service.create');
        Route::post('/plus-service/store', [PlusServiceController::class, 'store'])->name('plus-service.store');
        Route::get('/plus-service/edit/{uuid}', [PlusServiceController::class, 'edit'])->name('plus-service.edit');
        Route::put('/plus-service/update/{uuid}', [PlusServiceController::class, 'update'])->name('plus-service.update');
        Route::delete('/plus-service/delete/{uuid}', [PlusServiceController::class, 'destroy'])->name('plus-service.destroy');
        Route::post('/plus-service/list', [PlusServiceController::class, 'list'])->name('plus-service.list');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/validate-promosi', [TransaksiController::class, 'validatePromosi']);
        Route::post('/transaksi/list', [TransaksiController::class, 'list'])->name('transaksi.list');
    });
});
