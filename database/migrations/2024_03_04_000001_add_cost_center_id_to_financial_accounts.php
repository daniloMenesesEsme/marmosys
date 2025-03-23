<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('financial_accounts', function (Blueprint $table) {
            $table->foreignId('cost_center_id')->nullable()->after('category_id')->constrained('cost_centers');
        });
    }

    public function down()
    {
        Schema::table('financial_accounts', function (Blueprint $table) {
            $table->dropForeign(['cost_center_id']);
            $table->dropColumn('cost_center_id');
        });
    }
}; 