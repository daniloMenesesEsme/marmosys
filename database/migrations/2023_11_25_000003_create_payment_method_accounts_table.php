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
        if (!Schema::hasTable('payment_method_accounts')) {
            Schema::create('payment_method_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('descricao', 100);
                $table->foreignId('payment_method_id')->constrained();
                $table->decimal('taxa', 10, 2)->default(0);
                $table->integer('prazo_recebimento')->default(0);
                $table->decimal('saldo_inicial', 15, 2)->default(0);
                $table->date('data_saldo_inicial')->nullable();
                $table->boolean('ativo')->default(true);
                $table->string('codigo_externo', 30)->nullable();
                $table->string('chave_integracao', 100)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_accounts');
    }
}; 