<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\TanggapanController;
use Illuminate\Support\Facades\Route;

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

Route::get('/landingPage', function(){
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegisterMasyarakat'])->name('register');
Route::post('/register', [RegisterController::class, 'registerMasyarakat']);

Route::get('/login', [LoginController::class, 'showLoginMasyarakat'])->name('login');
Route::post('/login', [LoginController::class, 'loginMasyarakat']);
Route::get('/login/petugas', [LoginController::class, 'showLoginPetugas'])->name('login.petugas');
Route::post('/login/petugas', [LoginController::class, 'loginPetugas']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('isLogin')->group(function () {
    Route::middleware('isMasyarakat')->group(function (){
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan/store', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/edit/{id}', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
        Route::post('/pengaduan/update/{id}', [PengaduanController::class, 'update'])->name('pengaduan.update');
        Route::delete('/pengaduan/destroy/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    });

    Route::middleware('isAdmin')->group(function (){

        Route::get('/petugas/masyarakat', [MasyarakatController::class, 'index'])->name('masyarakat.index');

        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');
        Route::post('/petugas/update/{id}',[PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/destroy/{id}',[PetugasController::class, 'destroy'])->name('petugas.destroy');
        Route::get('/petugas/generate_pdf', [TanggapanController::class, 'generatePDF'])->name('generate.pdf');
        Route::get('/petugas/masyarakat/destroy/{id}', [MasyarakatController::class, 'destroy'])->name('masyarakat.destroy');

    });

    Route::middleware('isAdminPetugas')->group(function (){
        Route::get('/petugas/pengaduan', [PengaduanController::class, 'indexPetugas'])->name('pengaduan.indexPetugas');

        Route::delete('/petugas/pengaduan/destroy/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroyPetugas');

        Route::get('/tanggapan', [TanggapanController::class, 'index'])->name('tanggapan.index');
        Route::get('/tanggapan/create', [TanggapanController::class, 'create'])->name('tanggapan.create');
        Route::post('/tanggapan/store', [TanggapanController::class, 'store'])->name('tanggapan.store');
        Route::get('/tanggapan/edit/{id}', [TanggapanController::class, 'edit'])->name('tanggapan.edit');
        Route::post('/tanggapan/update/{id}', [TanggapanController::class, 'update'])->name('tanggapan.update');
        Route::delete('/tanggapan/destroy/{id}', [TanggapanController::class, 'destroy'])->name('tanggapan.destroy');

    });

});
