<?php

namespace App\Imports;

use App\Models\FinancialAgent;
use App\Models\FinancialCategory;
use App\Models\CostCenter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class FinancialAgentImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validar e converter tipo
        $tipo = strtolower($row['tipo']);
        if (!in_array($tipo, array_keys(FinancialAgent::TIPOS))) {
            $tipo = 'outros'; // Valor padrão se não for válido
        }

        // Buscar categoria pelo nome ou criar nova
        $financialCategoryId = null;
        if (!empty($row['categoria_financeira'])) {
            $category = FinancialCategory::firstOrCreate(
                ['nome' => $row['categoria_financeira']],
                [
                    'tipo' => 'analitica',
                    'natureza' => 'receita',
                    'ativo' => true
                ]
            );
            $financialCategoryId = $category->id;
        }

        // Buscar centro de custo pelo nome ou criar novo
        $costCenterId = null;
        if (!empty($row['centro_de_custo'])) {
            $costCenter = CostCenter::firstOrCreate(
                ['nome' => $row['centro_de_custo']],
                ['ativo' => true]
            );
            $costCenterId = $costCenter->id;
        }

        // Criar ou atualizar agente financeiro
        return new FinancialAgent([
            'codigo' => $row['codigo'],
            'nome' => $row['nome'],
            'tipo' => $tipo,
            'status' => strtolower($row['status'] ?? 'ativo') === 'ativo',
            'financial_category_id' => $financialCategoryId,
            'cost_center_id' => $costCenterId,
            'observacoes' => $row['observacoes'] ?? null,
            
            // Campos específicos para bancos
            'codigo_banco' => $row['codigo_banco'] ?? null,
            'agencia' => $row['agencia'] ?? null,
            'conta' => $row['conta'] ?? null,
            'digito' => $row['digito'] ?? null,
            
            // Campo específico para financeiras
            'cnpj' => $row['cnpj'] ?? null,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:20',
            'nome' => 'required|string|max:100',
            'tipo' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'codigo.required' => 'O código é obrigatório',
            'nome.required' => 'O nome é obrigatório',
            'tipo.required' => 'O tipo é obrigatório'
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
} 