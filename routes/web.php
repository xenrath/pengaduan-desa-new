<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    
    Route::get('pengaduan/menunggu', [\App\Http\Controllers\Pengaduan\MenungguController::class, 'index']);
    Route::get('pengaduan/menunggu/{id}', [\App\Http\Controllers\Pengaduan\MenungguController::class, 'show']);
    Route::get('pengaduan/menunggu/proses/{id}', [\App\Http\Controllers\Pengaduan\MenungguController::class, 'proses']);
    Route::get('pengaduan/menunggu/tolak/{id}', [\App\Http\Controllers\Pengaduan\MenungguController::class, 'tolak']);
    
    Route::get('pengaduan/proses', [\App\Http\Controllers\Pengaduan\ProsesController::class, 'index']);
    Route::get('pengaduan/proses/{id}', [\App\Http\Controllers\Pengaduan\ProsesController::class, 'show']);
    Route::post('pengaduan/proses/add-detail/{id}', [\App\Http\Controllers\Pengaduan\ProsesController::class, 'add_detail']);
    Route::get('pengaduan/proses/selesai/{id}', [\App\Http\Controllers\Pengaduan\ProsesController::class, 'selesai']);

    Route::get('pengaduan/riwayat', [\App\Http\Controllers\Pengaduan\RiwayatController::class, 'index']);
    Route::get('pengaduan/riwayat/{id}', [\App\Http\Controllers\Pengaduan\RiwayatController::class, 'show']);

    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::get('user/hubungi/{id}', [UserController::class, 'hubungi']);

    Route::resource('kategori', KategoriController::class);
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
