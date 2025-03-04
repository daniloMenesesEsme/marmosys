<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetMaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'nome' => 'Granito Preto São Gabriel',
                'codigo' => 'GRAN-001',
                'descricao' => 'Granito preto de alta qualidade',
                'preco_venda' => 350.00,
                'ativo' => true
            ],
            [
                'nome' => 'Mármore Branco Carrara',
                'codigo' => 'MARB-001',
                'descricao' => 'Mármore italiano de primeira linha',
                'preco_venda' => 450.00,
                'ativo' => true
            ],
            [
                'nome' => 'Granito Verde Ubatuba',
                'codigo' => 'GRAN-002',
                'descricao' => 'Granito verde brasileiro',
                'preco_venda' => 320.00,
                'ativo' => true
            ]
        ];

        foreach ($materials as $material) {
            DB::table('budget_materials')->insert(array_merge($material, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
} 