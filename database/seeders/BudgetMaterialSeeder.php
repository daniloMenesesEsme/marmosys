<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetMaterial;

class BudgetMaterialSeeder extends Seeder
{
    public function run()
    {
        BudgetMaterial::create([
            'codigo' => 'MAT001',
            'nome' => 'Granito Preto São Gabriel',
            'descricao' => 'Granito preto com cristais médios',
            'preco_venda' => 350.00,
            'unidade_medida' => 'm²',
            'ativo' => true
        ]);

        // Adicione mais materiais conforme necessário
    }
} 