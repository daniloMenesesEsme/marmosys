<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            // Primeiro, verifica se a coluna existe
            if (Schema::hasColumn('budget_items', 'descricao')) {
                $table->string('descricao')->nullable()->change();
            } else {
                $table->string('descricao')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            if (Schema::hasColumn('budget_items', 'descricao')) {
                $table->string('descricao')->nullable(false)->change();
            }
        });
    }
}; 