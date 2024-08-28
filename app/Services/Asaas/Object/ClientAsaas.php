<?php

namespace App\Services\Asaas\Object;

class ClientAsaas
{
    private string $name;
    private string $cpfCnpj;
    private string $email;

    public function __construct(string $name, string $cpfCnpj, string $email){
        $this->name = $name;
        $this->cpfCnpj = $cpfCnpj;
        $this->email = $email;
        $this->validate();
    }

    public function validate(){
        if($this->name == null){
            throw new \Exception("Nome é obrigatório");
        }
        if($this->cpfCnpj == null){
            throw new \Exception("Cpf/Cnpj é obrigatório");
        }
        if($this->email == null){
            throw new \Exception("Email é obrigatório");
        }
    }

    public function jsonSerialize(){
        return [
          "name" => $this->name,
          "cpfCnpj" => $this->cpfCnpj,
          "email" => $this->email
        ];
    }


}
