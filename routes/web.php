<?php

use App\Http\Controllers\PromosiController;
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
        // Rute-rute Departemen di sini
        Route::get('/promosi', [PromosiController::class, 'index'])->name('promosi.index');
        Route::get('/promosi/create', [PromosiController::class, 'create'])->name('promosi.create');
        Route::post('/promosi/store', [PromosiController::class, 'store'])->name('promosi.store');
        Route::get('/promosi/show/{uuid}', [PromosiController::class, 'show'])->name('promosi.show');
        Route::get('/promosi/edit/{uuid}', [PromosiController::class, 'edit'])->name('promosi.edit');
        Route::put('/promosi/update/{uuid}', [PromosiController::class, 'update'])->name('promosi.update');
        Route::delete('/promosi/delete/{uuid}', [PromosiController::class, 'destroy'])->name('promosi.destroy');
        Route::post('/promosi/list', [PromosiController::class, 'list'])->name('promosi.list');
    });
});
