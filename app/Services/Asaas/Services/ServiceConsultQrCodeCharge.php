<?php

namespace App\Services\Asaas\Services;


use App\Interfaces\ExecuteService;
use App\Models\Base\AsaasCobranca;
use App\Models\Student;
use App\Services\Asaas\Object\ClientAsaas;

class ServiceConsultQrCodeCharge implements ExecuteService
{
    private $endpoint;
    private $method;
    private AsaasCobranca $asaasCobranca;

    public function __construct(AsaasCobranca $asaasCobranca)
    {
        $this->method = 'GET';
        $this->asaasCobranca = $asaasCobranca;
        $this->endpoint = ENV('ASAAS_HOST') . "payments/".$this->asaasCobranca->id_charge."/pixQrCode";
    }

    public function getUrl()
    {
        return $this->endpoint;
    }

    public function setUrl($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function getType()
    {
        return get_class($this);
    }

    public function getId()
    {
        return 0;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUser()
    {
        return $this->student;
    }

    public function getPayload($service_id)
    {


    }
}
