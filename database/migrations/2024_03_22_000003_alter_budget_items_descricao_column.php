<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->string('descricao')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->string('descricao')->nullable(false)->change();
        });
    }
}; 