<?php

namespace App\Services\Asaas\Object;
class Split
{
    private string $walletId;#Identificador da carteira Asaas que será transferido
    private float|null $value;#Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
    private float|null $fixedValue;
    private float|null $percentualValue;
    private float|null $totalFixedValue;

    public function __construct(string $walletId, float|null $value = null, float|null $fixedValue = null, float|null $percentualValue = null , float|null $totalFixedValue = null)
    {
        $this->value = $value;
        $this->walletId = $walletId;
        $this->value = $value;
        $this->fixedValue = $fixedValue;
        $this->percentualValue = $percentualValue;
        $this->totalFixedValue = $totalFixedValue;
        $this->validate();
    }

    private function validate()
    {
        if ($this->walletId === null) {
            throw new \Exception("WalletId is required");
        }
        if ($this->value === null && $this->fixedValue === null && $this->percentualValue === null && $this->totalFixedValue === null) {
            throw new \Exception("Você precisa inserir um valor is required");

        }
        if ($this->value <= 0 && $this->fixedValue <= 0 && $this->percentualValue <= 0 && $this->totalFixedValue <= 0) {
            throw new \Exception("Você precisa inserir um valor maior que zero");
        }
    }
    public function  __toString()
    {
        return "{
           walletId: \"{$this->walletId}\",
           value: \"{$this->value}\",
           fixedValue: \"{$this->fixedValue}\",
           percentualValue: \"{$this->percentualValue}\",
           totalFixedValue: \"{$this->totalFixedValue}\"
        }";
    }
    public function jsonSerializable(){
        return [
            "walletId"=> $this->walletId,
            "value"=> $this->value,
            "fixedValue" => $this->value,
            "percentualValue" => $this->percentualValue,
            "totalFixedValue" => $this->totalFixedValue,
        ];
    }
}
