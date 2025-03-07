<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primeiro, adiciona a coluna
        Schema::table('products', function (Blueprint $table) {
            $table->string('tipo')->default('product')->after('descricao');
        });

        // Depois, atualiza os registros existentes
        DB::table('products')->update(['tipo' => 'product']);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}; 