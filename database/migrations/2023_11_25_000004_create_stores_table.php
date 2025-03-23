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
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('nome', 100);
                $table->string('razao_social', 150)->nullable();
                $table->string('cnpj', 14)->nullable();
                $table->string('inscricao_estadual', 20)->nullable();
                $table->string('telefone', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('endereco', 150)->nullable();
                $table->string('numero', 10)->nullable();
                $table->string('complemento', 50)->nullable();
                $table->string('bairro', 50)->nullable();
                $table->string('cidade', 50)->nullable();
                $table->string('estado', 2)->nullable();
                $table->string('cep', 8)->nullable();
                $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('stores');
    }
}; 