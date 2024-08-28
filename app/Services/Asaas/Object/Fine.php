<?php

namespace App\Services\Asaas\Object;
class Fine
{
    #Informações de multa para pagamento após o vencimento
    private float|null $value;#Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
    private Type|string $type;

    public function __construct(float $value, string $type)
    {
        $this->value = $value;
        $this->type = $type;
        $this->validate();
    }

    private function validate()
    {
        if ($this->value === null || $this->value <= 0) {
            throw new \Exception("Você precisa inserir um valor");
        }
        if ($this->type === null) {
            throw new \Exception("Você precisa inserir um tipo");
        }
    }

    public function __toString()
    {
        return "{
            value:$this->value,
            type:$this->type,
        }";
    }

    public function jsonSerializable(){
        return [
            'value'=>$this->value,
            'type'=>$this->type
        ];
    }
}
