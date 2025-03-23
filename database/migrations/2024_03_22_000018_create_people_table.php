<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('people')) {
            Schema::create('people', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('apelido')->nullable();
                $table->string('cpf_cnpj')->unique();
                $table->string('rg')->nullable();
                $table->string('crt')->nullable();
                $table->string('cnae')->nullable();
                $table->date('data_nascimento')->nullable();
                $table->string('telefone')->nullable();
                $table->boolean('is_cliente')->default(false);
                $table->boolean('is_fornecedor')->default(false);
                $table->boolean('is_vendedor')->default(false);
                $table->boolean('is_transportador')->default(false);
                $table->boolean('is_condutor')->default(false);
                $table->boolean('is_contador')->default(false);
                $table->boolean('is_intermediador')->default(false);
                $table->enum('tipo_pessoa', ['F', 'J'])->default('F'); // F = Física, J = Jurídica
                $table->enum('genero', ['M', 'F', 'O'])->nullable();
                $table->boolean('is_produtor_rural')->default(false);
                $table->string('cep')->nullable();
                $table->string('logradouro')->nullable();
                $table->string('numero')->nullable();
                $table->string('complemento')->nullable();
                $table->string('bairro')->nullable();
                $table->string('pais')->default('Brasil');
                $table->string('uf', 2)->nullable();
                $table->string('municipio')->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->text('observacoes')->nullable();
                $table->string('foto_path')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}; 