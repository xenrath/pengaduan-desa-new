<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
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
    Route::resource('kategori', KategoriController::class);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
