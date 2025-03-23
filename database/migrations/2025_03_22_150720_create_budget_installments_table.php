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
        Schema::create('budget_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->integer('numero_parcela');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->unsignedBigInteger('financial_account_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
            $table->foreign('financial_account_id')->references('id')->on('financial_accounts')->onDelete('set null');
            
            // Cada orçamento só pode ter uma parcela com o mesmo número
            $table->unique(['budget_id', 'numero_parcela']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_installments');
    }
};
