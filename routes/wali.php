<?php

use App\Http\Controllers\SantriController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('wali/santri', [SantriController::class, 'waliSantri'])
        ->name('wali.santri');
});
