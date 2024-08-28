<?php

namespace App\Services\Asaas\Object;

class CreditCard
{
    private string $holderName;
    private string $number;
    private string $expiryMonth;
    private string $expiryYear;
    private string $ccv;

    public function __construct(string $holderName = '', string $number = '', string $expiryMonth ='', string $expiryYear = '', string $ccv = '')
    {
        $this->holderName = $holderName;
        $this->number = $number;
        $this->expiryMonth = $expiryMonth;
        $this->expiryYear = $expiryYear;
        $this->ccv = $ccv;
        $this->validate();
    }

    private function validate()
    {
        if ($this->holderName == '') {
            throw new \Exception("Nome é obrigatório!");
        }
        if ($this->number == '') {
            throw new \Exception("Numero do cartão é obrigatório!");
        }
        if ($this->expiryMonth < 1 || $this->expiryMonth > 12 || strlen($this->expiryMonth) < 2 || strlen($this->expiryMonth) > 2) {
            throw new \Exception("Mês de expiração inválido! deve ser entre 1 e 12");
        }

        if (strlen($this->expiryYear) < 4 || strlen($this->expiryYear) > 4) {
            throw new \Exception("Ano de expiração inválido !");
        }

        if ($this->ccv === '') {
            throw new \Exception("Cvv é obrigatório inválido !");
        }
    }

    public function __toString()
    {
        return "{
        holderName: $this->holderName,
        number: $this->number,
        expiryMonth: $this->expiryMonth,
        expiryYear: $this->expiryYear,
        ccv: $this->ccv
        }";
    }

    public function string()
    {
        return "{holderName: $this->holderName, number: $this->number, expiryMonth: $this->expiryMonth, expiryYear: $this->expiryYear,cvv: $this->ccv}";
    }

    public function jsonSerializable()
    {
        return [
            "holderName" => $this->holderName,
            "number" => $this->number,
            "expiryMonth" => $this->expiryMonth,
            "expiryYear" => $this->expiryYear,
            "ccv" => $this->ccv
        ];
    }
}
