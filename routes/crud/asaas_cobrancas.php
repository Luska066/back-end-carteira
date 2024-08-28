<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('asaas_cobrancas', App\Http\Controllers\AsaasCobrancaController::class, []);
    Route::put('/asaas_cobranca/{cobranca}/asaas/service', [App\Http\Controllers\AsaasCobrancaController::class, 'asaasCobrancaConsultServiceAsaas']);
});
