<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Adiciona relação em payment_methods
        if (Schema::hasTable('payment_methods') && !Schema::hasColumn('payment_methods', 'financial_agent_id')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->foreignId('financial_agent_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('financial_agents')
                    ->nullOnDelete();
            });
        }

        // Adiciona relação em financial_transactions
        if (Schema::hasTable('financial_transactions') && !Schema::hasColumn('financial_transactions', 'financial_agent_id')) {
            Schema::table('financial_transactions', function (Blueprint $table) {
                $table->foreignId('financial_agent_id')
                    ->nullable()
                    ->after('financial_category_id')
                    ->constrained('financial_agents')
                    ->nullOnDelete();
            });
        }

        // Adiciona relação em bank_accounts
        if (Schema::hasTable('bank_accounts') && !Schema::hasColumn('bank_accounts', 'financial_agent_id')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->foreignId('financial_agent_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('financial_agents')
                    ->nullOnDelete();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('payment_methods') && Schema::hasColumn('payment_methods', 'financial_agent_id')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->dropForeign(['financial_agent_id']);
                $table->dropColumn('financial_agent_id');
            });
        }

        if (Schema::hasTable('financial_transactions') && Schema::hasColumn('financial_transactions', 'financial_agent_id')) {
            Schema::table('financial_transactions', function (Blueprint $table) {
                $table->dropForeign(['financial_agent_id']);
                $table->dropColumn('financial_agent_id');
            });
        }

        if (Schema::hasTable('bank_accounts') && Schema::hasColumn('bank_accounts', 'financial_agent_id')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->dropForeign(['financial_agent_id']);
                $table->dropColumn('financial_agent_id');
            });
        }
    }
}; 