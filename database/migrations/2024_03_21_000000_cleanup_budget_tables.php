<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Desabilita verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Remove as tabelas na ordem correta
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budget_rooms');
        Schema::dropIfExists('budget_materials');
        Schema::dropIfExists('budgets');

        // Reabilita verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Não é necessário fazer nada aqui
    }
}; 