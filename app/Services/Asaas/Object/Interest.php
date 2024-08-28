<?php

namespace App\Services\Asaas\Object;
class Interest
{
    #Informações de juros para pagamento após o vencimento
    private float|null $value;#Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança

    public function __construct(float $value)
    {
        $this->value = $value;
        $this->validate();
    }

    private function validate()
    {
        if ($this->value === null || $this->value <= 0) {
            throw new \Exception("Você precisa inserir um valor");
        }
    }

    public function __toString()
    {
        return "{
            value:$this->value,
        }";
    }

    public function jsonSerializable(){
        return [
            "value" => $this->value
        ];
    }
}

