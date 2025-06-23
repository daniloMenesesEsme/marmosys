<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Remove tudo que não for número
        $value = preg_replace('/[^0-9]/', '', $value);
        
        // Verifica se está vazio
        if (empty($value)) {
            return false;
        }
        
        // Verifica o tamanho (8 para fixo antigo, 9 para celular + 2 do DDD = 10 ou 11)
        $length = strlen($value);
        if ($length < 10 || $length > 11) {
            return false;
        }
        
        // Verifica DDD válido (11 a 99)
        $ddd = substr($value, 0, 2);
        if ($ddd < 11 || $ddd > 99) {
            return false;
        }
        
        // Se for celular (11 dígitos), primeiro dígito deve ser 9
        if ($length == 11 && substr($value, 2, 1) != '9') {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1+$/', $value)) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O :attribute informado não é válido.';
    }
}
