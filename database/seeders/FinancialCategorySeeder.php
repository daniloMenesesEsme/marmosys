<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Receitas
            [
                'nome' => 'Vendas',
                'tipo' => 'ANALITICA',
                'natureza' => 'receita',
                'descricao' => 'Receitas provenientes de vendas de produtos e serviços',
                'ativo' => true,
                'cor' => '#28a745',
                'icone' => 'shopping_cart'
            ],
            [
                'nome' => 'Pagamento de Projetos',
                'tipo' => 'ANALITICA',
                'natureza' => 'receita',
                'descricao' => 'Receitas de pagamentos de projetos e contratos',
                'ativo' => true,
                'cor' => '#20c997',
                'icone' => 'assignment'
            ],
            [
                'nome' => 'Investimentos',
                'tipo' => 'ANALITICA',
                'natureza' => 'receita',
                'descricao' => 'Receitas provenientes de investimentos financeiros',
                'ativo' => true,
                'cor' => '#17a2b8',
                'icone' => 'trending_up'
            ],
            [
                'nome' => 'Outras Receitas',
                'tipo' => 'SINTETICA',
                'natureza' => 'receita',
                'descricao' => 'Receitas diversas não classificadas em outras categorias',
                'ativo' => true,
                'cor' => '#007bff',
                'icone' => 'attach_money'
            ],
            
            // Despesas
            [
                'nome' => 'Salários',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com folha de pagamento e benefícios',
                'ativo' => true,
                'cor' => '#dc3545',
                'icone' => 'people'
            ],
            [
                'nome' => 'Fornecedores',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Pagamentos a fornecedores de materiais e insumos',
                'ativo' => true,
                'cor' => '#fd7e14',
                'icone' => 'local_shipping'
            ],
            [
                'nome' => 'Aluguel',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com aluguel de imóveis e equipamentos',
                'ativo' => true,
                'cor' => '#e83e8c',
                'icone' => 'home'
            ],
            [
                'nome' => 'Serviços Públicos',
                'tipo' => 'SINTETICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com água, luz, telefone e internet',
                'ativo' => true,
                'cor' => '#6f42c1',
                'icone' => 'power'
            ],
            [
                'nome' => 'Impostos',
                'tipo' => 'SINTETICA',
                'natureza' => 'despesa',
                'descricao' => 'Pagamentos de impostos e taxas governamentais',
                'ativo' => true,
                'cor' => '#6c757d',
                'icone' => 'gavel'
            ],
            [
                'nome' => 'Marketing',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com publicidade e marketing',
                'ativo' => true,
                'cor' => '#ffc107',
                'icone' => 'campaign'
            ],
            [
                'nome' => 'Manutenção',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com manutenção de equipamentos e instalações',
                'ativo' => true,
                'cor' => '#87ceeb',
                'icone' => 'build'
            ],
            [
                'nome' => 'Transporte',
                'tipo' => 'ANALITICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas com combustível, frete e entregas',
                'ativo' => true,
                'cor' => '#ff69b4',
                'icone' => 'directions_car'
            ],
            [
                'nome' => 'Outras Despesas',
                'tipo' => 'SINTETICA',
                'natureza' => 'despesa',
                'descricao' => 'Despesas diversas não classificadas em outras categorias',
                'ativo' => true,
                'cor' => '#343a40',
                'icone' => 'money_off'
            ]
        ];

        $now = Carbon::now();

        foreach ($categories as $category) {
            DB::table('financial_categories')->insert(array_merge($category, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
} 