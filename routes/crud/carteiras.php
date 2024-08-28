<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('carteiras', App\Http\Controllers\CarteiraController::class, []);
    
});
