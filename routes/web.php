<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryBlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DoorprizeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PlusServiceController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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


Route::get('/', [LandingPageController::class, 'landingPage'])->name('landingPage');
Route::post('/register-memberships', [MembershipController::class, 'register'])->name('membership.register');
Route::post('/memberships/extend', [MembershipController::class, 'extend'])->name('memberships.extend');
Route::get('/list-blog', [LandingPageController::class, 'index'])->name('blog-landingPage');
Route::get('/list-blog/{slug}', [LandingPageController::class, 'showBlog'])->name('listBlog-detail');
// Route::get('/list-blog', function () {
//     return view('LandingPage.blog');
// })->name('blog-landingPage');

// Route::prefix('/list-blog')->group(function () {
//     Route::get('/detail-blog', function () {
//         return view('LandingPage.detail-blog');
//     })->name('listBlog-detail');
// });


Route::post('/track-order', [TrackController::class, 'tracking'])->name('tracking');
Route::post('/advice', [AdviceController::class, 'postAdvice'])->name('advice');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/get-hadiah-data', [DoorprizeController::class, 'getHadiahData'])->name('get-hadiah-data');
    Route::get('/hadiah-data', [DoorprizeController::class, 'getHadiah'])->name('hadiah-data');
    Route::post('/pick-doorprize-winner', [DoorprizeController::class, 'pickDoorprizeWinner'])->name('pick-doorprize-winner');
    Route::prefix('dashboard')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/edit/{uuid}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/update/{uuid}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete/{uuid}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::post('/user/list', [UserController::class, 'list'])->name('user.list');
    });
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
        Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
        Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
        Route::post('/memberships/store', [MembershipController::class, 'store'])->name('memberships.store');
        Route::get('/memberships/show/{uuid}', [MembershipController::class, 'show'])->name('memberships.show');
        Route::get('/memberships/edit/{uuid}', [MembershipController::class, 'edit'])->name('memberships.edit');
        Route::put('/memberships/update/{uuid}', [MembershipController::class, 'update'])->name('memberships.update');
        Route::delete('/memberships/delete/{uuid}', [MembershipController::class, 'destroy'])->name('memberships.destroy');
        Route::post('/memberships/verify/{uuid}', [MembershipController::class, 'verify'])->name('memberships.verify');
        Route::post('/memberships/list', [MembershipController::class, 'list'])->name('memberships.list');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/kategori-blog', [CategoryBlogController::class, 'index'])->name('kategori-blog.index');
        Route::get('/kategori-blog/create', [CategoryBlogController::class, 'create'])->name('kategori-blog.create');
        Route::post('/kategori-blog/store', [CategoryBlogController::class, 'store'])->name('kategori-blog.store');
        Route::get('/kategori-blog/show/{uuid}', [CategoryBlogController::class, 'show'])->name('kategori-blog.show');
        Route::get('/kategori-blog/edit/{uuid}', [CategoryBlogController::class, 'edit'])->name('kategori-blog.edit');
        Route::put('/kategori-blog/update/{uuid}', [CategoryBlogController::class, 'update'])->name('kategori-blog.update');
        Route::delete('/kategori-blog/delete/{uuid}', [CategoryBlogController::class, 'destroy'])->name('kategori-blog.destroy');
        Route::post('/kategori-blog/list', [CategoryBlogController::class, 'list'])->name('kategori-blog.list');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/show/{uuid}', [BlogController::class, 'show'])->name('blog.show');
        Route::get('/blog/edit/{uuid}', [BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/update/{uuid}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/delete/{uuid}', [BlogController::class, 'destroy'])->name('blog.destroy');
        Route::post('/blog/list', [BlogController::class, 'list'])->name('blog.list');
        Route::post('/blog/publish/{uuid}', [BlogController::class, 'publishBlog'])->name('blog.publish');
        Route::post('/blog/delete/post/{uuid}', [BlogController::class, 'deleteBlog'])->name('blog.delete');
        Route::post('/blog/draft/{uuid}', [BlogController::class, 'draftBlog'])->name('blog.draft');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [CategoryController::class, 'create'])->name('kategori.create');
        Route::post('/kategori/store', [CategoryController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/show/{uuid}', [CategoryController::class, 'show'])->name('kategori.show');
        Route::get('/kategori/edit/{uuid}', [CategoryController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/update/{uuid}', [CategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/delete/{uuid}', [CategoryController::class, 'destroy'])->name('kategori.destroy');
        Route::delete('/kategori/delete-subkategori/{uuid}', [CategoryController::class, 'deleteSubCategory'])->name('kategori.deleteSubKategori');
        // Route untuk menampilkan form tambah sub-kategori
        Route::get('/kategori/subkategori/{uuid}', [CategoryController::class, 'tambahSubKategori'])->name('kategori.tambahSubKategori');
        Route::get('/kategori/showsub/{uuid}', [CategoryController::class, 'showSubCategory'])->name('kategori.detailSubKategori');
        Route::get('/kategori/{uuid}/activate', [CategoryController::class, 'activateCategory'])->name('kategori.activate');
        Route::get('/kategori/{uuid}/deactivate', [CategoryController::class, 'deactivateCategory'])->name('kategori.deactivate');

        // Route untuk menyimpan sub-kategori
        Route::post('/kategorisubkategori/{uuid}', [CategoryController::class, 'storeSubCategory'])->name('kategori.storeSubCategory');

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
        Route::put('/plus-service/{uuid}/activate', [PlusServiceController::class, 'activate'])->name('plus-service.activate');
        Route::put('/plus-service/{uuid}/deactivate', [PlusServiceController::class, 'deactivate'])->name('plus-service.deactivate');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/show/{uuid}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/transaksi/{uuid}/proses', [TransaksiController::class, 'proses'])->name('transaksi.proses');
        Route::get('/transaksi/{uuid}/finish', [TransaksiController::class, 'finish'])->name('transaksi.finish');
        Route::get('/transaksi/{uuid}/revisi', [TransaksiController::class, 'revisi'])->name('transaksi.revisi');
        Route::get('/transaksi/{uuid}/cetak-pdf', [TransaksiController::class, 'cetak_pdf'])->name('transaksi.cetak_pdf');
        Route::post('/transaksi/{id}/pelunasan', [TransaksiController::class, 'pelunasan'])->name('transaksi.pelunasan');
        Route::post('/transaksi/{id}/update-pickup', [TransaksiController::class, 'updateStatusPickup'])->name('transaksi.updatePickup');
        Route::get('/validate-promosi', [TransaksiController::class, 'validatePromosi']);
        Route::get('/validate-membership', [TransaksiController::class, 'validateMembership']);
        Route::post('/transaksi/list', [TransaksiController::class, 'list'])->name('transaksi.list');
    });
});
