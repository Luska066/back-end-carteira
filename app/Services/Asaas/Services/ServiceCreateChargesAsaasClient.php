<?php

namespace App\Services\Asaas\Services;

use App\Interfaces\ExecuteService;
use App\Models\Student;
use App\Services\Asaas\Object\ChargeAsaas;
use App\Services\Asaas\EnumObjectAsaas\BillingType;

class ServiceCreateChargesAsaasClient implements ExecuteService
{
    private $endpoint;
    private $method;
    private Student $student;
    private string $carteira_id;

    public function __construct(Student $student,string $carteira_id)
    {
        $this->method = 'POST';
        $this->student = $student;
        $this->carteira_id = $carteira_id;
        $this->endpoint = ENV('ASAAS_HOST') . "payments"; #https://sandbox.asaas.com/api
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
        /*  Exemplo Payload
            {
                billingType: 'PIX',
                customer: 'cus_G7Dvo4iphUNk',
                value: 100,
                dueDate: '2017-06-10',
                description: 'Pedido 056984',
                daysAfterDueDateToRegistrationCancellation: 1,
                externalReference: '056984',
                installmentCount: 'parcelas',
                discount: {value: 100, dueDateLimitDays: 2, type: 'FIXED'},
                interest: {value: 100},
                fine: {value: 100, type: 'FIXED'},
                postalService: false,
                split: [
                  {walletId: '123', fixedValue: 123, totalFixedValue: 123, percentualValue: 123},
                  {walletId: '123', fixedValue: 123, percentualValue: 123, totalFixedValue: 123}
                ]
            }
        */

        $client = new ChargeAsaas(
            BillingType::PIX->value,
            $this->student->asaas_client->costumer_id,
            100.00,
            now()->addDay(),
            $this->carteira_id
        );
        return $client->jsonSerialize();
    }
}
