<?php

namespace App\Services\Asaas\Object;

//    billingType: 'PIX',
//    customer: 'cus_G7Dvo4iphUNk',
//    value: 100,
//    dueDate: '2017-06-10',
//    description: 'Pedido 056984',
//    daysAfterDueDateToRegistrationCancellation: 1,
//    externalReference: '056984',
//    installmentCount: 'parcelas',
//    discount: {value: 100, dueDateLimitDays: 2, type: 'FIXED'},
//    interest: {value: 100},
//    fine: {value: 100, type: 'FIXED'},
//    postalService: true,
//    split: [
//      {walletId: 'walletId', fixedValue: 2, percentualValue: 33, totalFixedValue: 100}
//    ]

use App\Services\Asaas\Object\BillingType;
use App\Services\Asaas\Object\Discount;
use App\Services\Asaas\Object\Fine;
use App\Services\Asaas\Object\Interest;

class ChargeAsaas
{
    private BillingType|string $billingType;
    private string $customer;
    private float $value;
    private \Carbon|string $dueDate;
    private string|null $description;
    private int|null $daysAfterDueDateToRegistrationCancellation; #Dias após o vencimento para cancelamento do registro (somente para boleto bancário)
    private string|null $externalReference;#Campo livre para busca
    private int|null $installmentCount; #Número de parcelas (somente no caso de cobrança parcelada)
    private Discount|array|null $discount; #Desconto
    private Interest|array|null $interest;
    private Fine|array|null $fine;
    private bool $postalService; #Define se a cobrança será enviada via Correios
    private array $split;


    public function __construct(
        string|null   $billingType,
        string|null   $customer,
        float|null    $value,
        string|null   $dueDate,
        string|null   $externalReference = '',
        string|null   $description = '',
        int|null      $daysAfterDueDateToRegistrationCancellation = 0,
        int|string|null  $installmentCount = null,
        Discount|array|null $discount = null,
        Interest|array|null $interest = null,
        Fine|array|null     $fine = null,
        bool|null     $postalService = false,
        array|null    $split = []
    )
    {
        $this->billingType = $billingType;
        $this->customer = $customer;
        $this->value = $value;
        $this->dueDate = $dueDate;
        $this->description = $description;
        $this->daysAfterDueDateToRegistrationCancellation = $daysAfterDueDateToRegistrationCancellation;
        $this->externalReference = $externalReference;
        $this->installmentCount = $installmentCount;
        $this->discount = $discount;
        $this->interest = $interest;
        $this->fine = $fine;
        $this->postalService = $postalService;
        $this->split =$split;
        $this->validate();
    }

    private function validate(){
        if($this->customer === null){
            throw new \Exception("Id do cliente é obrigatória");
        }
        if($this->billingType === null){
            throw new \Exception("Forma de Pagamento é obrigatória");
        }
        if($this->value === null || $this->value <= 0){
            throw new \Exception("Forma de Pagamento é obrigatória");
        }
        if($this->dueDate === null){
            throw new \Exception("Data de vencimento é obrigatória");
        }else{
            if($this->dueDate <= now()){
                throw new \Exception("A data de vencimento tem que ser maior que hoje!");
            }
        }
    }

    public function __toString(){

        return "[{
            billingType: $this->billingType,
            customer: $this->customer,
            value: $this->value,
            dueDate: $this->dueDate,
            description: $this->description,
            daysAfterDueDateToRegistrationCancellation: $this->daysAfterDueDateToRegistrationCancellation,
            externalReference: $this->externalReference,
            postalService: " . ($this->postalService ? 'true' : 'false') . ",
            installmentCount: $this->installmentCount,
            discount:".$this->discount.",
            fine: $this->fine,
            split: $this->split,
            interest: $this->interest,
        }]";
    }

    public function jsonSerialize(){
        return [
            "billingType"=> $this->billingType,
            "customer"=> $this->customer,
            "value"=> $this->value,
            "dueDate"=> $this->dueDate,
            "description"=> $this->description,
            "daysAfterDueDateToRegistrationCancellation"=> $this->daysAfterDueDateToRegistrationCancellation,
            "externalReference"=> $this->externalReference,
            "postalService"=> $this->postalService,
            "installmentCount"=> $this->installmentCount,
            "discount" =>$this->discount,
            "fine" => $this->fine,
            "split" => $this->split,
            "interest" => $this->interest,
        ];
    }

}







