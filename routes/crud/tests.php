<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('test', App\Http\Controllers\TestController::class, []);
    
});
