<?php

namespace App\Helpers;

class StringUtils
{

    public static function formatCpf(string $cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) === 11) {
            // Formata o CPF no padrão XXX.XXX.XXX-XX
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }

        return $cpf;
    }
}
