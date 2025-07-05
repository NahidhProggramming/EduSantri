<?php

use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PembagianKelasController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes/api.php
Route::get('/sekolah-by-tahun/{tahunId}', [NilaiController::class, 'getSekolahByTahun']);
Route::get('/kelas-by-sekolah/{sekolahId}/tahun/{tahunId}', [NilaiController::class, 'getKelasBySekolahTahun']);
Route::get('/santri-by-kelas', [NilaiController::class, 'getSantriByKelas']);


