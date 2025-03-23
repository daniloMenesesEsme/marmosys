<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id')->nullable()->after('approved_at');
            $table->string('payment_condition')->nullable()->after('payment_method_id');
            $table->integer('payment_installments')->default(1)->after('payment_condition');
            $table->decimal('payment_fee', 8, 2)->default(0)->after('payment_installments');
            $table->date('first_installment_date')->nullable()->after('payment_fee');
            $table->boolean('converted_to_receivable')->default(false)->after('first_installment_date');
            
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn([
                'payment_method_id',
                'payment_condition',
                'payment_installments',
                'payment_fee',
                'first_installment_date',
                'converted_to_receivable'
            ]);
        });
    }
};
