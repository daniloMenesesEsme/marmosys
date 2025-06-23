<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidCep implements Rule
{
    protected $message = 'O :attribute informado não é válido.';

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
            $this->message = 'O CEP é obrigatório.';
            return false;
        }
        
        // Verifica se tem 8 dígitos
        if (strlen($value) !== 8) {
            $this->message = 'O CEP deve ter 8 dígitos.';
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1+$/', $value)) {
            $this->message = 'O CEP informado é inválido.';
            return false;
        }
        
        try {
            // Consulta o CEP na API dos Correios
            $response = Http::timeout(3)
                ->retry(2, 100)
                ->get("https://viacep.com.br/ws/{$value}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verifica se o CEP existe
                if (isset($data['erro']) && $data['erro'] === true) {
                    $this->message = 'O CEP informado não foi encontrado.';
                    return false;
                }
                
                return true;
            }
            
            $this->message = 'Não foi possível validar o CEP no momento. Tente novamente.';
            return false;
            
        } catch (\Exception $e) {
            $this->message = 'Não foi possível validar o CEP no momento. Tente novamente.';
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Formata o CEP para exibição
     *
     * @param string $cep
     * @return string
     */
    public static function format($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    }

    /**
     * Remove a formatação do CEP
     *
     * @param string $cep
     * @return string
     */
    public static function unformat($cep)
    {
        return preg_replace('/[^0-9]/', '', $cep);
    }
}
