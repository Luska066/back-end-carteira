<?php

namespace App\Services\Asaas\Services;


use App\Interfaces\ExecuteService;
use App\Models\Student;
use App\Services\Asaas\Object\ClientAsaas;

class ServiceCreateAsaasClient implements ExecuteService
{
    private $endpoint;
    private $method;
    private Student $student;

    public function __construct(Student $student)
    {
        $this->method = 'POST';
        $this->student = $student;
        $this->endpoint = ENV('ASAAS_HOST') . "customers";
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
        $costumer = new ClientAsaas(
            $this->student->name."",
            $this->student->cpf."",
            $this->student->email.""
        );

       return $costumer->jsonSerialize();

    }
}
