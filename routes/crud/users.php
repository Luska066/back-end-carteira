<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class, []);
    Route::get('/profile', [App\Http\Controllers\UserController::class,"profile"])->name('profile');
    Route::get('/change-password', [App\Http\Controllers\UserController::class,"changePassword"])->name('change-password');
    Route::get('/configuracao-plataforma', [App\Http\Controllers\UserController::class,"configuracaoPlataforma"])->name('configuracao-plataforma');

});
