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
        Schema::create('current_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 100);
            $table->foreignId('bank_id')->constrained();
            $table->string('agencia', 20);
            $table->string('conta', 20);
            $table->string('digito', 5)->nullable();
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->date('data_saldo_inicial')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('codigo_externo', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_accounts');
    }
}; 