<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('email')->nullable();
                $table->string('telefone')->nullable();
                $table->string('cpf')->unique()->nullable();
                $table->string('endereco')->nullable();
                $table->string('cargo');
                $table->date('data_admissao');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}; 