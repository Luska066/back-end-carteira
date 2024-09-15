<?php

namespace App\Services;


use App\Models\Servico;
use App\Services\Asaas\Asaas;
use App\Interfaces\ExecuteService as InterfaceService;
use Illuminate\Support\Facades\Log;

class ExecuteService
{
    private $product;
    private $service;

    public function __construct(InterfaceService $product, Servico $service = null)
    {
        $this->product = $product;
        if($service == null)
        {
            $service = $this->createService();
        }
        $this->service = $service;
        Log::info('Construtor ExecuteService', [
            'service_id'  =>  $this->service->id,
            'product_type'  =>  $this->product->getType(),
            'product_id'  =>  $this->product->getId()
        ]);
    }

    public function request()
    {
        Log::info('Request ExecuteService', [
            'service_id'  =>  $this->service->id,
            'product_type'  =>  $this->product->getType(),
            'product_id'  =>  $this->product->getId()
        ]);
        try {
            $asaas = new Asaas();
            Log::info('Inicia request Rendimento', [
                'service_id'  =>  $this->service->id,
                'product_type'  =>  $this->product->getType(),
                'product_id'  =>  $this->product->getId()
            ]);
            $response = null;
            if($this->service->method != "GET"){
                $response = $asaas->request(
                    $this->service->method,
                    $this->service->url,
                    array(
                        "body" => $this->service->payload
                    )
                );
            }
            if($this->service->method == "GET"){
                $response = $asaas->request(
                    $this->service->method,
                    $this->service->url,
                );
            }

            Log::info('Fim request Rendimento', [
                'service_id'  =>  $this->service->id,
                'product_type'  =>  $this->product->getType(),
                'product_id'  =>  $this->product->getId(),
                'response'  =>  $response
            ]);

            if(!is_string($response))
            { // caso o retorno seja um objetio de erro, é necessario registrar de acordo
                Log::error('Corpo da requisição não é string: '.json_encode($response->getBody()), [
                    'service_id'  =>  $this->service->id,
                    'product_type'  =>  $this->product->getType(),
                    'product_id'  =>  $this->product->getId()
                ]);
                return $this->response($response->getBody()->getContents(), true);
            }

            return $this->response($response);
        } catch (\Throwable $th) {
            Log::error('Erro desconhecido', [
                'service_id'  =>  $this->service->id,
                'product_type'  =>  $this->product->getType(),
                'product_id'  =>  $this->product->getId()
            ]);
            Log::error($th->getMessage(), [
                'service_id'  =>  $this->service->id,
                'product_type'  =>  $this->product->getType(),
                'product_id'  =>  $this->product->getId()
            ]);
            return $this->response($th->getMessage(), true);
        }
    }

    private function response($response, $response_error = false)
    {
        // verifica se foi informado serviçe, caso tenha sido, apenas o atualiza
        Log::error($response, [
            'service_id'  =>  $this->service->id,
            'product_type'  =>  $this->product->getType(),
            'product_id'  =>  $this->product->getId()
        ]);
        $this->service->update([
            'response'  =>  $response,
            'exec'  =>  1
        ]);

        $response = json_decode($response, true);
        $response['consult_id'] = $this->service->id;

        return $response_error ? false : $response;
    }

    private function createService()
    {
        $service = Servico::create([
            'url'   =>  $this->product->getUrl(),
            'method'   =>  $this->product->getMethod(),
            'service_type'  =>  $this->product->getType(),
            'service_id'   =>  $this->product->getId(),
            'payload'   =>  '',
            'exec'  =>  0,
            "user_id" => auth()?->user()?->id ?? null
        ]);

        $service->update([
            'payload'   =>  json_encode($this->product->getPayload($service->id)),
        ]);

        return $service;
    }

}
