<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->prefix('/v1')->group( function () {
    Route::middleware('can:student')->group(function (){
        require 'api/student_api_v1.php';
    });
});



Route::prefix('public')->group( function () {
    Route::post('/student/web-socket', function (Request $request) {
        try {
            \Illuminate\Support\Facades\Log::info("request websocket",[
                "request" => $request
            ]);
            \App\Events\PaymentResponse::dispatch($request->user());
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::info("Error ao executar websocket",[
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            dd($e->getMessage());
        }
        \Illuminate\Support\Facades\Log::info("SOCKET ENVIADO");
    });
   Route::post('prelogin',[\App\Http\Controllers\AuthFrontEndApplicationController::class,"preLoginAction"]) ;
   Route::post('create-student',[\App\Http\Controllers\StudentController::class,"criarUserEstudanteeContaAsaas"]) ;
});
