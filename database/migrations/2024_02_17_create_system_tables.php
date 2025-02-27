<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Categorias Financeiras
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo'); // receita ou despesa
            $table->boolean('ativo')->default(true);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // 3. Centro de Custos (movido para antes de financial_accounts)
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // 4. Clientes
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->text('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // 5. Produtos
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco_venda', 10, 2);
            $table->decimal('preco_custo', 10, 2)->default(0);
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->decimal('estoque', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // 6. Orçamentos
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->decimal('valor', 10, 2);
            $table->integer('mes');
            $table->integer('ano');
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('financial_categories')
                  ->onDelete('cascade');
        });

        // 7. Contas Financeiras (por último, pois depende de várias outras tabelas)
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->string('tipo'); // receita ou despesa
            $table->string('status')->default('pendente'); // pendente, pago, cancelado
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('documento')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('financial_categories')
                  ->onDelete('cascade');

            $table->foreign('cost_center_id')
                  ->references('id')
                  ->on('cost_centers')
                  ->onDelete('set null');

            $table->foreign('budget_id')
                  ->references('id')
                  ->on('budgets')
                  ->onDelete('set null');
        });

        // Metas Financeiras
        Schema::create('financial_goals', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor_meta', 10, 2);
            $table->decimal('valor_atual', 10, 2)->default(0);
            $table->date('data_inicial');
            $table->date('data_final');
            $table->decimal('percentual', 5, 2)->default(0);
            $table->string('status')->default('em_andamento'); // em_andamento, concluida, cancelada
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Ordem inversa da criação
        Schema::dropIfExists('financial_accounts');
        Schema::dropIfExists('financial_goals');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('products');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('cost_centers');
        Schema::dropIfExists('financial_categories');
        Schema::dropIfExists('users');
    }
}; 