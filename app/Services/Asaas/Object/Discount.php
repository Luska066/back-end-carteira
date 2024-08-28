<?php

namespace App\Services\Asaas\Object;
use App\Services\Asaas\Object\Type;
class Discount
{
    private float|null $value;#Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
    private int $dueDateLimitDays; #Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante;
    private Type|string $type;

    public function __construct(float $value = null, int $dueDateLimitDays = 0, string $type = null )
    {
        $this->value = $value;
        $this->dueDateLimitDays = $dueDateLimitDays;
        $this->type = $type;
        $this->validate();
    }

    private function validate()
    {
        if ($this->value === null || $this->value <= 0) {
            throw new \Exception("Você precisa inserir um valor");
        }
        if ($this->dueDateLimitDays === null) {
            throw new \Exception("Você precisa inserir dias para o vencimento");
        }
        if (!isset($this->type)) {
            throw new \Exception("Você precisa inserir um tipo");
        }
    }

    public function __toString()
    {
        return "{
            value:$this->value,
            dueDateLimitDays:$this->dueDateLimitDays,
            type:$this->type,
        }";
    }

    public function jsonSerializable(): array {
        $this->validate();
        return [
            "value" => $this->value,
            "dueDateLimitDays" => $this->dueDateLimitDays,
            "type" => $this->type
        ];
    }
}

