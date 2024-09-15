<?php

namespace App\Enums;

enum Steps:string
{
    case CADASTRO = "Cadastro";
    case FOTO = "Foto";
    case PAGAMENTO = "Pagamento";
    case AGUARDANDOPAGAMENTO = "Aguardando Pagamento";
    case DOWNLOAD = "Download";
}
