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
            'financial_category_id' => 'nullable|exists:financial_categories,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'observacoes' => 'nullable|string|max:500'
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
            $rules['cnpj'] = [
                'required',
                'string',
                'size:14',
                'regex:/^\d{14}$/',
                Rule::unique('financial_agents', 'cnpj')->ignore($agentId)
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'O código é obrigatório',
            'codigo.max' => 'O código não pode ter mais que 20 caracteres',
            'codigo.unique' => 'Este código já está em uso',
            
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome não pode ter mais que 100 caracteres',
            
            'tipo.required' => 'O tipo é obrigatório',
            'tipo.in' => 'O tipo selecionado é inválido',
            
            'financial_category_id.required' => 'A categoria financeira é obrigatória',
            'financial_category_id.exists' => 'A categoria financeira selecionada é inválida',
            
            'cost_center_id.required' => 'O centro de custo é obrigatório',
            'cost_center_id.exists' => 'O centro de custo selecionado é inválido',
            
            'codigo_banco.required' => 'O código do banco é obrigatório',
            'codigo_banco.max' => 'O código do banco não pode ter mais que 3 caracteres',
            
            'agencia.required' => 'A agência é obrigatória',
            'agencia.max' => 'A agência não pode ter mais que 10 caracteres',
            
            'conta.required' => 'A conta é obrigatória',
            'conta.max' => 'A conta não pode ter mais que 20 caracteres',
            
            'digito.required' => 'O dígito é obrigatório',
            'digito.max' => 'O dígito não pode ter mais que 2 caracteres',
            
            'cnpj.required' => 'O CNPJ é obrigatório',
            'cnpj.size' => 'O CNPJ deve ter 14 dígitos',
            'cnpj.regex' => 'O CNPJ deve conter apenas números',
            'cnpj.unique' => 'Este CNPJ já está em uso',
            
            'observacoes.max' => 'As observações não podem ter mais que 500 caracteres'
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'nome' => 'nome',
            'tipo' => 'tipo',
            'status' => 'status',
            'financial_category_id' => 'categoria financeira',
            'cost_center_id' => 'centro de custo',
            'codigo_banco' => 'código do banco',
            'agencia' => 'agência',
            'conta' => 'conta',
            'digito' => 'dígito',
            'cnpj' => 'CNPJ',
            'observacoes' => 'observações'
        ];
    }
} 