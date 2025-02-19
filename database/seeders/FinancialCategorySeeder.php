<?php

namespace Database\Seeders;

use App\Models\FinancialCategory;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Receitas
            ['nome' => 'Vendas', 'tipo' => 'receita'],
            ['nome' => 'Serviços', 'tipo' => 'receita'],
            ['nome' => 'Recebimentos', 'tipo' => 'receita'],
            
            // Despesas
            ['nome' => 'Fornecedores', 'tipo' => 'despesa'],
            ['nome' => 'Funcionários', 'tipo' => 'despesa'],
            ['nome' => 'Pró-labore', 'tipo' => 'despesa'],
            ['nome' => 'Aluguel', 'tipo' => 'despesa'],
            ['nome' => 'Energia', 'tipo' => 'despesa'],
            ['nome' => 'Água', 'tipo' => 'despesa'],
            ['nome' => 'Internet', 'tipo' => 'despesa'],
            ['nome' => 'Material de Escritório', 'tipo' => 'despesa'],
            ['nome' => 'Manutenção', 'tipo' => 'despesa'],
            ['nome' => 'Impostos', 'tipo' => 'despesa'],
        ];

        foreach ($categories as $category) {
            // Verifica se já existe uma categoria com mesmo nome e tipo
            FinancialCategory::firstOrCreate(
                ['nome' => $category['nome'], 'tipo' => $category['tipo']],
                $category
            );
        }
    }
} 