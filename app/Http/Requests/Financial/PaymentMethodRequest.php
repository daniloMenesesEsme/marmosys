<?php

namespace App\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            // ... existing rules ...
            'financial_agent_id' => [
                'nullable',
                'exists:financial_agents,id',
                function ($attribute, $value, $fail) {
                    $especieDocumento = $this->input('especie_documento');
                    if (in_array($especieDocumento, ['cartao_credito', 'cartao_debito', 'boleto']) && empty($value)) {
                        $fail('O agente financeiro é obrigatório para este tipo de método de pagamento.');
                    }
                }
            ]
        ];

        return $rules;
    }
} 