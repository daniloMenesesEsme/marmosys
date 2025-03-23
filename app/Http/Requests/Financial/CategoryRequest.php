<?php

namespace App\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:ANALITICA,SINTETICA',
            'natureza' => 'required|string|in:receita,despesa',
            'codigo_contabil' => 'nullable|string|max:50',
            'ativo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'tipo.required' => 'O tipo é obrigatório',
            'tipo.in' => 'O tipo selecionado é inválido. Use Analítica ou Sintética.',
            'natureza.required' => 'A natureza é obrigatória',
            'natureza.in' => 'A natureza selecionada é inválida. Use Receita ou Despesa.'
        ];
    }
} 