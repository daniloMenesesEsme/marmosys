<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20); // receita, despesa
            $table->string('descricao');
            $table->decimal('valor', 15, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->string('status', 20)->default('pendente'); // pendente, pago, cancelado
            $table->foreignId('categoria_id')->nullable()->constrained('financial_categories', 'id')->nullOnDelete();
            $table->foreignId('conta_id')->nullable()->constrained('financial_accounts', 'id')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clients', 'id')->nullOnDelete();
            $table->foreignId('orcamento_id')->nullable()->constrained('budgets', 'id')->nullOnDelete();
            $table->foreignId('financial_agent_id')->nullable()->constrained('financial_agents', 'id')->nullOnDelete();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
}; 