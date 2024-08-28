<?php

namespace App\Services\Asaas\Object;

class Taxes
{
    #Informações de multa para pagamento após o vencimento
    private bool $retainIss;
    private float $iss;
    private float $cofins;
    private float $csll;
    private float $inss;
    private float $ir;
    private float $pis;

    public function __construct(
        bool $retainIss = false,
        float $iss = 0.0,
        float $cofins = 0.0,
        float $csll = 0.0,
        float $inss = 0.0,
        float $ir = 0.0,
        float $pis = 0.0
    )
    {
        $this->retainIss = $retainIss;
        $this->iss = $iss;
        $this->cofins = $cofins;
        $this->csll = $csll;
        $this->inss = $inss;
        $this->ir = $ir;
        $this->pis = $pis;
        $this->validate();
    }

    private function validate()
    {

        if ($this->iss === null || $this->iss <= 0 ) {
            throw new \Exception("Você precisa inserir um valor iss");
        }
        if ( $this->iss > 6 ) {
            throw new \Exception("Valor máximo do iss é 6%");
        }
        if ($this->cofins === null || $this->cofins <= 0) {
            throw new \Exception("Você precisa inserir um valor cofins");
        }
        if ($this->csll === null || $this->csll <= 0) {
            throw new \Exception("Você precisa inserir um valor csll");
        }
        if ($this->inss === null || $this->inss <= 0) {
            throw new \Exception("Você precisa inserir um valor inss");
        }
        if ($this->ir === null || $this->ir <= 0) {
            throw new \Exception("Você precisa inserir um valor ir");
        }
        if ($this->pis === null || $this->pis <= 0) {
            throw new \Exception("Você precisa inserir um valor pis");
        }

    }

    public function __toString()
    {
        return "{retainIss: $this->retainIss,iss: $this->iss,cofins: $this->cofins,csll: $this->csll,inss: $this->inss,ir: $this->ir,pis: $this->pis}";
    }

    public function string()
    {
        $reatinInss = $this->retainIss ? "true" : "false";
        return "{retainIss: $reatinInss,iss: $this->iss,cofins: $this->cofins,csll: $this->csll,inss: $this->inss,ir: $this->ir,pis: $this->pis}";
    }
    public function isEmpty(){
        if($this->retainIss === false && $this->iss === 0.0 && $this->cofins === 0.0 && $this->csll === 0.0 && $this->inss === 0.0 && $this->ir === 0.0 && $this->pis === 0.0){
            return true;
        }
        return false;
    }

    public function jsonSerializable(){
        return [
            "retainIss" => $this->retainIss,
            "iss" => $this->iss,
            "cofins" => $this->cofins,
            "csll" => $this->csll,
            "inss" => $this->inss,
            "ir" => $this->ir,
            "pis" => $this->pis
        ];
    }
}
