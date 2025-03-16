<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['venda', 'compra']);
            
            // Configurações de Parcelas
            $table->integer('parcelas');
            $table->integer('intervalo_dias');
            $table->integer('carencia_dias')->default(0);
            $table->decimal('taxa', 5, 2)->default(0);
            $table->decimal('multa_atraso', 5, 2)->default(0);
            $table->decimal('juros_atraso', 5, 2)->default(0);
            
            // Configurações de Uso
            $table->boolean('permite_entrada')->default(false);
            $table->decimal('percentual_minimo_entrada', 5, 2)->default(0);
            $table->decimal('valor_minimo_parcela', 10, 2)->default(0);
            $table->decimal('limite_credito', 10, 2)->default(0);
            
            // Integrações
            $table->foreignId('payment_method_id')->constrained(); // Forma de Pagamento
            $table->string('codigo_xml', 50)->nullable(); // Código usado no XML NFe
            
            // Configurações PDV
            $table->boolean('disponivel_pdv')->default(true);
            $table->integer('ordem_exibicao')->default(0);
            
            // Controles
            $table->boolean('ativo')->default(true);
            $table->boolean('requer_aprovacao')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela para restrições de uso
        Schema::create('payment_plan_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_plan_id')->constrained()->onDelete('cascade');
            $table->enum('tipo_restricao', ['produto', 'categoria', 'cliente', 'fornecedor']);
            $table->unsignedBigInteger('restricao_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_plan_restrictions');
        Schema::dropIfExists('payment_plans');
    }
}; 