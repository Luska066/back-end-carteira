<?php
namespace App\Services\Assas\EnumObjectAsaas;
enum Cycles: string
{
    case WEEKLY = 'WEEKLY';
    case MONTHLY = 'MONTHLY';
    case BIWEEKLY = 'BIWEEKLY';
    case BIMONTHLY = 'BIMONTHLY';
    case QUARTELY = 'QUARTELY';
    case SEMIANNUALLY = 'SEMIANNUALLY';
    case YEARLY = 'YEARLY';
}
