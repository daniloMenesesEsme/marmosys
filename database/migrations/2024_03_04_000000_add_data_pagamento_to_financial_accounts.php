<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('financial_accounts', function (Blueprint $table) {
            $table->date('data_pagamento')->nullable()->after('data_vencimento');
        });
    }

    public function down()
    {
        Schema::table('financial_accounts', function (Blueprint $table) {
            $table->dropColumn('data_pagamento');
        });
    }
}; 