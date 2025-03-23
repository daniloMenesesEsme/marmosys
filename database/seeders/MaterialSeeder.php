<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'nome' => 'Granito Preto São Gabriel',
                'codigo' => 'PSG001',
                'descricao' => 'Granito preto com cristais médios',
                'preco_padrao' => 350.00,
                'unidade_medida' => 'm²',
                'estoque_minimo' => 10.00,
                'estoque_atual' => 50.00,
                'preco_custo' => 250.00,
                'preco_venda' => 350.00,
                'ativo' => true
            ],
            [
                'nome' => 'Mármore Branco Carrara',
                'codigo' => 'MBC001',
                'descricao' => 'Mármore branco italiano',
                'preco_padrao' => 450.00,
                'unidade_medida' => 'm²',
                'estoque_minimo' => 10.00,
                'estoque_atual' => 30.00,
                'preco_custo' => 350.00,
                'preco_venda' => 450.00,
                'ativo' => true
            ],
            // Adicione mais materiais conforme necessário
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
} 