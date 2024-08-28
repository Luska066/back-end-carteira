<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
Route::middleware(['auth'])->group(function () {
    Route::resource('students', StudentController::class, []);

});
