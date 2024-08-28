<?php

namespace App\Services\Asaas;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class Asaas
{
    private $httpClient;
    private $grant_type;
    private $token;
    private $authorization;
    private $client_id;
    private $chave_acesso;
    private $useraname;
    private $password;
    private $host;

    public function __construct()
    {
        $this->host = env('ASAAS_HOST').'/';
        $this->token = env('ASAAS_KEY');
        $this->httpClient = new GuzzleHttpClient([
            'base_uri' => $this->host,
            'timeout' => 30,
        ]);
    }

    public function request($method, $endpoint, $options = [])
    {
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'access_token' => $this->token,
            "Content-Type" => "application/json",
            "User-Agent" => "lucas.santsena@gmail.com"
        ]);
        Log::info("Method : ". json_encode($method));
        Log::info("Endpoint : ". json_encode($endpoint));
        Log::info("Options request: ". json_encode($options));
        try {
            return $this->httpClient->request($method, $endpoint, $options)->getBody()->getContents();
        }
        catch (ClientException $e) {
            return $e->getResponse();
        }
    }

}
