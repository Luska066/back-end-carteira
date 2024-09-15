<?php

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
Route::middleware('redirectadmin')->group(function (){
    \San\Crud\Crud::routes();
    Route::get('/home', [App\Http\Controllers\UserController::class, 'index'])->name('home');
});
Route::get('',function ( ){
   return redirect()->route('login');
});
Auth::routes();



