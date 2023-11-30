<?php

use App\Http\Controllers\Api\KomentarController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('verifikasi/{id}', [UserController::class, 'verifikasi']);
Route::get('otp/{id}', [UserController::class, 'otp']);
Route::get('user/show/{id}', [UserController::class, 'show']);

Route::get('pengaduan/list-all', [PengaduanController::class, 'list_all']);
Route::get('pengaduan/list/{user_id}', [PengaduanController::class, 'list']);
Route::post('pengaduan/store', [PengaduanController::class, 'store']);
Route::get('pengaduan/show/{id}', [PengaduanController::class, 'show']);
Route::get('pengaduan/list-proses/{id}', [PengaduanController::class, 'list_proses']);
Route::get('pengaduan/list-komentar/{id}', [PengaduanController::class, 'list_komentar']);
Route::get('pengaduan/get-kategori', [PengaduanController::class, 'get_kategori']);

Route::post('komentar/store', [KomentarController::class, 'store']);
