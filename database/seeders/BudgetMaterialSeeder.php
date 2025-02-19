<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetMaterial;

class BudgetMaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'nome' => 'Granito Preto São Gabriel',
                'preco_padrao' => 350.00,
                'unidade_medida' => 'm²'
            ],
            [
                'nome' => 'Mármore Branco Carrara',
                'preco_padrao' => 450.00,
                'unidade_medida' => 'm²'
            ],
            [
                'nome' => 'Granito Cinza Corumbá',
                'preco_padrao' => 280.00,
                'unidade_medida' => 'm²'
            ],
            // Adicione mais materiais conforme necessário
        ];

        foreach ($materials as $material) {
            BudgetMaterial::create($material);
        }
    }
} 