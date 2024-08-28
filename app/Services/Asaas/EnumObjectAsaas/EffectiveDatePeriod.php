<?php
namespace App\Services\Assas\EnumObjectAsaas;
enum EffectiveDatePeriod: string
{
    case ON_PAYMENT_CONFIRMATION = 'ON_PAYMENT_CONFIRMATION';
    case ON_PAYMENT_DUE_DATE = 'ON_PAYMENT_DUE_DATE';
    case BEFORE_PAYMENT_DUE_DATE = 'BEFORE_PAYMENT_DUE_DATE';
    case ON_DUE_DATE_MONTH = 'ON_DUE_DATE_MONTH';
    case ON_NEXT_MONTH = 'ON_NEXT_MONTH';
}
