<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('financial_categories', function (Blueprint $table) {
            $table->string('natureza')->after('tipo')->default('receita');
        });

        // Atualiza os registros existentes
        DB::table('financial_categories')->update(['natureza' => 'receita']);
    }

    public function down()
    {
        Schema::table('financial_categories', function (Blueprint $table) {
            $table->dropColumn('natureza');
        });
    }
}; 