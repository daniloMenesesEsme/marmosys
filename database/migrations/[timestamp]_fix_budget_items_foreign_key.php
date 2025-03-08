<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            // Remove a foreign key antiga se existir
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('budget_items');
            
            foreach ($foreignKeys as $key) {
                if ($key->getLocalColumns()[0] === 'material_id') {
                    $table->dropForeign($key->getName());
                }
            }

            // Adiciona a nova foreign key para products
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