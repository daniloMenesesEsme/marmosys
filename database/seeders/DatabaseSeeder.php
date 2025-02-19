<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Models\FinancialGoal;
use App\Models\CostCenter;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::beginTransaction();
            
            // Criar usuário admin
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456')
            ]);

            // Criar categorias financeiras
            $categoriaReceita = FinancialCategory::create([
                'nome' => 'Vendas',
                'tipo' => 'receita',
                'ativo' => true,
                'descricao' => 'Receitas provenientes de vendas'
            ]);

            $categoriaDespesa = FinancialCategory::create([
                'nome' => 'Despesas Operacionais',
                'tipo' => 'despesa',
                'ativo' => true,
                'descricao' => 'Despesas relacionadas à operação'
            ]);

            // Criar algumas contas financeiras
            FinancialAccount::create([
                'descricao' => 'Venda #001',
                'valor' => 1500.00,
                'category_id' => $categoriaReceita->id,
                'tipo' => 'receita',
                'status' => 'pendente',
                'data_vencimento' => now()->addDays(30)
            ]);

            FinancialAccount::create([
                'descricao' => 'Aluguel',
                'valor' => 2000.00,
                'category_id' => $categoriaDespesa->id,
                'tipo' => 'despesa',
                'status' => 'pendente',
                'data_vencimento' => now()->addDays(15)
            ]);

            // Criar alguns clientes
            Client::create([
                'nome' => 'Cliente Teste',
                'email' => 'cliente@teste.com',
                'telefone' => '(11) 99999-9999',
                'ativo' => true,
                'cpf_cnpj' => '123.456.789-00',
                'endereco' => 'Rua Teste, 123',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567'
            ]);

            // Criar alguns produtos
            Product::create([
                'codigo' => 'PROD001',
                'nome' => 'Produto Teste',
                'preco_venda' => 99.90,
                'preco_custo' => 50.00,
                'estoque' => 10,
                'estoque_minimo' => 5,
                'ativo' => true,
                'descricao' => 'Descrição do produto teste'
            ]);

            // Criar centros de custo
            CostCenter::create([
                'nome' => 'Administrativo',
                'descricao' => 'Despesas administrativas',
                'ativo' => true
            ]);

            CostCenter::create([
                'nome' => 'Comercial',
                'descricao' => 'Despesas comerciais',
                'ativo' => true
            ]);

            CostCenter::create([
                'nome' => 'Operacional',
                'descricao' => 'Despesas operacionais',
                'ativo' => true
            ]);

            // Criar metas financeiras
            FinancialGoal::create([
                'descricao' => 'Fundo de Reserva',
                'valor_meta' => 50000.00,
                'valor_atual' => 15000.00,
                'data_inicial' => now(),
                'data_final' => now()->addYear(),
                'status' => 'em_andamento',
                'observacoes' => 'Meta para criar fundo de reserva da empresa',
                'percentual' => 30 // (15000/50000) * 100
            ]);

            FinancialGoal::create([
                'descricao' => 'Redução de Custos',
                'valor_meta' => 10000.00,
                'valor_atual' => 3000.00,
                'data_inicial' => now(),
                'data_final' => now()->addMonths(6),
                'status' => 'em_andamento',
                'observacoes' => 'Reduzir custos operacionais'
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $this->call([
            BudgetMaterialSeeder::class,
            MaterialSeeder::class,
            // ... outros seeders
        ]);
    }
} 