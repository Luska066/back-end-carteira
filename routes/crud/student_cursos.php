<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
Route::middleware(['auth'])->group(function () {
    Route::get('find/courses/{student}', [\App\Models\StudentCurso::class, 'buscarCursoComBaseNoEstudante']);
});
