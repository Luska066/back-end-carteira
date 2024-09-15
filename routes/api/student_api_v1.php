<?php

use App\Enums\Steps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\RegistrationStep;
use App\Models\Step;
use App\Http\Controllers\AuthFrontEndApplicationController;
use \Illuminate\Support\Facades\Log;
use \App\Models\AsaasCobranca;
use \App\Events\PaymentResponse;
use \App\Models\AsaasClient;
use \App\Enums\EventWebhookAsaas;

Route::get('/student/get-step', [AuthFrontEndApplicationController::class, 'getSteps']);
Route::get('/verify', [AuthFrontEndApplicationController::class, 'verify']);
Route::post('/student/send-image', [AuthFrontEndApplicationController::class, 'updateImage']);
Route::post('/student/generate-card', [AuthFrontEndApplicationController::class, 'createCard']);
Route::get('/student/consult-qr-code', [AuthFrontEndApplicationController::class, 'consultQrCode']);
Route::get('/student/websocket-identify', [AuthFrontEndApplicationController::class, 'websocketGetIdentify']);
Route::get('/student/uuid', [AuthFrontEndApplicationController::class, 'getStudentUuid']);
Route::get('/student/card', [AuthFrontEndApplicationController::class, 'getCard']);
Route::get('/student/has/execute/payment', [AuthFrontEndApplicationController::class, 'jaExecuteiPagamento']);

Route::post('/student/download/card',[AuthFrontEndApplicationController::class, 'downloadCard']);
Route::post('/student/download/archive/card',[AuthFrontEndApplicationController::class, 'downloadArchivePdf']);



Route::post('/student/webhook', function (Request $request) {
    try {
        $webhook = $request->all();
        Log::info("Webhook Payload", [
            "payload" => $request->all()
        ]);
        if($webhook["event"] == EventWebhookAsaas::PAYMENT_RECEIVED->value){
            $asaas_client = AsaasClient::query()->where('costumer_id',$webhook['payment']['customer'])->first();
            $user = $asaas_client->student()->first()->user()->first();

            if (empty($asaas_client)) {
                Log::error("Cliente não encontrado no sistema");
            }
            if(!empty($asaas_client)){
                Log::error("Cliente encontrado no sistema");

            }

            $cobranca = AsaasCobranca::where('id_charge', $webhook["payment"]["id"])->first();
            if (empty($cobranca)) {
                Log::error("Cobrança não encontrada no sistema");
            }

            if(!empty($cobranca)){
                $cobranca->status = $webhook["payment"]["status"];
                $cobranca->save();
                $student = $asaas_client->student()->first();
                RegistrationStep::query()->updateOrCreate([
                    'id_student' => $student->id,
                    'id_step'=> Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
                ],[
                    'id_step'=> Step::where('name',Steps::AGUARDANDOPAGAMENTO->value)->first()->id,
                    'id_student' => $student->id,
                ]);
            }
            PaymentResponse::dispatch($user);
        }

    } catch (Exception $e) {
        dd($e->getTrace());
    }
    Log::info("SOCKET ENVIADO", []);
});


