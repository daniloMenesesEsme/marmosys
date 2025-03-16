<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('financial_categories', function (Blueprint $table) {
            $table->enum('tipo', ['analitica', 'sintetica'])->default('analitica')->after('nome');
            $table->string('codigo_contabil_externo', 20)->nullable()->after('tipo');
        });

        // Define todas as categorias existentes como analíticas por padrão
        DB::table('financial_categories')->update(['tipo' => 'analitica']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_categories', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'codigo_contabil_externo']);
        });
    }
};
