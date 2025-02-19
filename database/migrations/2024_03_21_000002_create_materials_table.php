<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->unique()->nullable();
            $table->text('descricao')->nullable();
            $table->decimal('preco_padrao', 10, 2);
            $table->string('unidade_medida');
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->decimal('estoque_atual', 10, 2)->default(0);
            $table->decimal('preco_custo', 10, 2)->default(0);
            $table->decimal('preco_venda', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->foreignId('category_id')->nullable()->constrained('material_categories')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}; 