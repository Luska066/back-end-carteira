<?php

namespace App\Services\Asaas\Object;

use App\Helpers\ValidateDocument;

class CreditCardHolderInfo
{
    private string $name;
    private string $email;
    private string $cpfCnpj;
    private string $postalCode;
    private string $addressNumber;
    private string $addressComplement;
    private string $phone;
    private string $mobilePhone;

    public function __construct(
        string $name = null ,
        string $email = null,
        string $cpfCnpj = null,
        string $postalCode = null,
        string $addressNumber = null,
        string $addressComplement = null,
        string $phone = null,
        string $mobilePhone = null
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->cpfCnpj = $cpfCnpj;
        $this->postalCode = $postalCode;
        $this->addressNumber = $addressNumber;
        $this->addressComplement = $addressComplement;
        $this->phone = $phone;
        $this->mobilePhone = $mobilePhone;
    }

    private function validate()
    {
        if ($this->name == null) {
            throw new \Exception("Nome é obrigatório!");
        }

        if ($this->email == null) {
            throw new \Exception("Numero do cartão é obrigatório!");
        }

        if($this->cpfCnpj == null){
            throw new \Exception("Cpf ou cnpj é obrigatório (a)!");
        }

        if(strlen($this->cpfCnpj) === 11){
            if(ValidateDocument::CPF($this->cpfCnpj)){
                throw new \Exception("Cpf inválido!");
            }
        }

        if(strlen($this->cpfCnpj) === 14){
            if(ValidateDocument::CNPJ($this->cpfCnpj)){
                throw new \Exception("Cnpj inválido!");
            }
        }

        if ($this->postalCode === null) {
            throw new \Exception("Cep é obrigatório !");
        }

        if ($this->addressNumber === null) {
            throw new \Exception("Número de endereço é obrigatório !");
        }

        if ($this->phone === null) {
            throw new \Exception("Número de telefone é obrigatório !");
        }

    }

    public function __toString()
    {
        return "{name : $this->name,email : $this->email,cpfCnpj : $this->cpfCnpj,postalCode : $this->postalCode,addressNumber : $this->addressNumber,addressComplement : $this->addressComplement,phone : $this->phone,mobilePhone : $this->mobilePhone}";
    }
    public function string()
    {
        return "{name : $this->name,email : $this->email,cpfCnpj : $this->cpfCnpj,postalCode : $this->postalCode,addressNumber : $this->addressNumber, addressComplement : $this->addressComplement, phone : $this->phone,mobilePhone : $this->mobilePhone}";
    }

    public function jsonSerializable()
    {
        return [
            "name" => $this->name,
            "email" => $this->email,
            "cpfCnpj" => $this->cpfCnpj,
            "postalCode" => $this->postalCode,
            "addressNumber" => $this->addressNumber,
            "addressComplement" => $this->addressComplement,
            "phone" => $this->phone,
            "mobilePhone" => $this->mobilePhone,
        ];
    }
}

