<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('carteiras', App\Http\Controllers\CarteiraController::class, []);
    Route::put('carteiras/generate-card/{student}',[App\Http\Controllers\AuthFrontEndApplicationController::class,'masterGenerateCard'])->name('master.generate-card');
});
