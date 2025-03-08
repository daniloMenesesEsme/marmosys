<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->decimal('preco_venda', 10, 2);
            $table->decimal('preco_custo', 10, 2);
            $table->decimal('estoque', 10, 2)->default(0);
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 