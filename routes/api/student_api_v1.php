<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('can:student')->get('/teste',function (Request $request){
    return 'ok';
});

