<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaculdadeAPI
{
    private $httpClient;

    private $token;

    private $host;
    private $query;
    private $baseUri;

    public function __construct(string $nome, string $birthDate, string $document)
    {
        $this->host = env('HOST_API_FACULDADE');
        $this->query = "?name=$nome&birthDate=$birthDate&document=$document";
        $this->token = env('TOKEN_API_FACULDADE');
        $this->baseUri = $this->host . $this->query . "&token=$this->token";
        $this->httpClient = new GuzzleHttpClient([
            'base_uri' => $this->host . $this->query . "&token=$this->token",
            'timeout' => 30,
        ]);
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function request($method, $endpoint, $options = [])
    {
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'access_token' => $this->token,
            "Content-Type" => "application/json",
            "User-Agent" => "lucas.santsena@gmail.com"
        ]);
        Log::info("Method : " . json_encode($method));
        Log::info("Endpoint : " . json_encode($endpoint));
        Log::info("Options request: " . json_encode($options));
        try {
            return $this->httpClient->request($method, $endpoint, $options)->getBody()->getContents();
        } catch (ClientException $e) {
            return $e->getResponse();
        }
    }

}
