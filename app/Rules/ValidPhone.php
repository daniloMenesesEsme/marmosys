<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        
        // Verifica se é um número de telefone válido (8 a 11 dígitos)
        if (!empty($value) && !preg_match('/^(?:(?:[14689][1-9]|2[12478]|3[1234578]|5[1345]|7[134579])9\d{8}|(?:[14689][1-9]|2[12478]|3[1234578]|5[1345]|7[134579])\d{7})$/', $value)) {
            $fail('O número de telefone informado não é válido.');
        }
    }
}
