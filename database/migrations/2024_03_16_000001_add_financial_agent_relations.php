<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Adiciona relação em payment_methods
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->foreignId('financial_agent_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('financial_agents')
                  ->nullOnDelete();
        });

        // Adiciona relação em financial_transactions
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->foreignId('financial_agent_id')
                  ->nullable()
                  ->after('financial_category_id')
                  ->constrained('financial_agents')
                  ->nullOnDelete();
        });

        // Adiciona relação em bank_accounts
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreignId('financial_agent_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('financial_agents')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropForeignIdFor('financial_agent_id');
        });

        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropForeignIdFor('financial_agent_id');
        });

        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeignIdFor('financial_agent_id');
        });
    }
}; 