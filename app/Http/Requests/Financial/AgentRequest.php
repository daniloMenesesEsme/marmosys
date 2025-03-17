<?php

namespace App\Http\Requests\Financial;

use App\Models\FinancialAgent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agentId = $this->route('agent')?->id;

        $rules = [
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('financial_agents', 'codigo')->ignore($agentId)
            ],
            'nome' => [
                'required',
                'string',
                'max:100'
            ],
            'tipo' => [
                'required',
                Rule::in(array_keys(FinancialAgent::TIPOS))
            ],
            'status' => 'boolean',
            'observacoes' => 'nullable|string|max:500',
        ];

        // Regras específicas por tipo de agente
        if ($this->tipo === 'banco') {
            $rules['financial_category_id'] = 'required|exists:financial_categories,id';
            $rules['cost_center_id'] = 'required|exists:cost_centers,id';
            $rules['codigo_banco'] = 'required|string|max:3';
            $rules['agencia'] = 'required|string|max:10';
            $rules['conta'] = 'required|string|max:20';
            $rules['digito'] = 'required|string|max:2';
        } elseif ($this->tipo === 'financeira') {
            $rules['financial_category_id'] = 'required|exists:financial_categories,id';
            $rules['cost_center_id'] = 'nullable|exists:cost_centers,id';
            $rules['cnpj'] = 'required|string|max:14';
        } else {
            $rules['financial_category_id'] = 'nullable|exists:financial_categories,id';
            $rules['cost_center_id'] = 'nullable|exists:cost_centers,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'O código é obrigatório',
            'codigo.unique' => 'Este código já está em uso',
            'nome.required' => 'O nome é obrigatório',
            'tipo.required' => 'O tipo é obrigatório',
            'tipo.in' => 'Tipo inválido',
            'financial_category_id.required' => 'A categoria financeira é obrigatória para este tipo de agente',
            'cost_center_id.required' => 'O centro de custo é obrigatório para este tipo de agente',
            'codigo_banco.required' => 'O código do banco é obrigatório',
            'agencia.required' => 'A agência é obrigatória',
            'conta.required' => 'A conta é obrigatória',
            'digito.required' => 'O dígito é obrigatório',
            'cnpj.required' => 'O CNPJ é obrigatório para financeiras',
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo' => 'Código',
            'nome' => 'Nome',
            'tipo' => 'Tipo',
            'financial_category_id' => 'Categoria Financeira',
            'cost_center_id' => 'Centro de Custo',
            'codigo_banco' => 'Código do Banco',
            'agencia' => 'Agência',
            'conta' => 'Conta',
            'digito' => 'Dígito',
            'cnpj' => 'CNPJ',
            'observacoes' => 'Observações'
        ];
    }
} 