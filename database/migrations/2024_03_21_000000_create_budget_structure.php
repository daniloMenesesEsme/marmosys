<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Criar tabela de materiais primeiro
        Schema::create('budget_materials', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco_venda', 10, 2);
            $table->decimal('preco_custo', 10, 2)->default(0);
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->decimal('estoque_atual', 10, 2)->default(0);
            $table->string('unidade_medida')->default('m²');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Criar tabela de orçamentos
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->date('data');
            $table->date('previsao_entrega');
            $table->foreignId('client_id')->constrained();
            $table->string('status')->default('aguardando_aprovacao');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_final', 10, 2);
            $table->date('data_validade');
            $table->text('observacoes')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Criar tabela de ambientes
        Schema::create('budget_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Criar tabela de itens
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('budget_materials');
            $table->decimal('quantidade', 10, 3);
            $table->string('unidade');
            $table->string('descricao');
            $table->decimal('largura', 10, 3);
            $table->decimal('altura', 10, 3);
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budget_rooms');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('budget_materials');
    }
}; 