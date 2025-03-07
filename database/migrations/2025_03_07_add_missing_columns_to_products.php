<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Adiciona as colunas que faltam
            if (!Schema::hasColumn('products', 'unidade_medida')) {
                $table->string('unidade_medida')->nullable()->after('estoque_minimo');
            }
            if (!Schema::hasColumn('products', 'categoria')) {
                $table->string('categoria')->nullable()->after('unidade_medida');
            }
            if (!Schema::hasColumn('products', 'fornecedor')) {
                $table->string('fornecedor')->nullable()->after('categoria');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unidade_medida', 'categoria', 'fornecedor']);
        });
    }
}; 