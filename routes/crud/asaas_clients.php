<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('asaas_clients', App\Http\Controllers\AsaasClientController::class, []);
    
});
