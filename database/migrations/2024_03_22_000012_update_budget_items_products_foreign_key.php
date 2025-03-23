<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Primeiro, verifica se a foreign key existe
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_NAME = 'budget_items'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_SCHEMA = DATABASE()
        ");

        // Remove todas as foreign keys existentes em material_id
        foreach ($foreignKeys as $key) {
            if (strpos($key->CONSTRAINT_NAME, 'material_id') !== false) {
                Schema::table('budget_items', function (Blueprint $table) use ($key) {
                    $table->dropForeign($key->CONSTRAINT_NAME);
                });
            }
        }

        // Adiciona a nova foreign key
        Schema::table('budget_items', function (Blueprint $table) {
            $table->foreign('material_id')
                ->references('id')
                ->on('products')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
        });
    }
}; 