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
            $table->enum('natureza', ['receita', 'despesa'])->after('tipo');

            // Migra os dados do campo tipo para natureza
            DB::statement("UPDATE financial_categories SET natureza = tipo");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_categories', function (Blueprint $table) {
            $table->dropColumn('natureza');
        });
    }
};
