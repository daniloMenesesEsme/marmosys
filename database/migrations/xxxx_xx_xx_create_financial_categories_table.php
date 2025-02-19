<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo'); // 'receita' ou 'despesa'
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_categories');
    }
};