<?php

namespace App\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'forma_pagamento' => 'nullable|string|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
            'status' => 'nullable|string',
            'cliente_id' => 'nullable|exists:clientes,id'
        ];
    }

    public function messages(): array
    {
        return [
            'data_fim.after_or_equal' => 'A data final deve ser maior ou igual à data inicial',
            'forma_pagamento.in' => 'Forma de pagamento inválida',
            'cliente_id.exists' => 'Cliente não encontrado'
        ];
    }
} 