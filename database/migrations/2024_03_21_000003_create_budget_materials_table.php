<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_materials', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('preco_padrao', 10, 2);
            $table->string('unidade_medida')->default('m²');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_materials');
    }
}; 