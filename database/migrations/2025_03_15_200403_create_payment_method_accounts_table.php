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
        Schema::create('payment_method_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('conta_corrente_id');
            $table->timestamps();

            $table->foreign('payment_method_id')
                  ->references('id')
                  ->on('payment_methods')
                  ->onDelete('cascade');

            $table->foreign('loja_id')
                  ->references('id')
                  ->on('stores')
                  ->onDelete('cascade');

            $table->foreign('conta_corrente_id')
                  ->references('id')
                  ->on('bank_accounts')
                  ->onDelete('cascade');

            // Garante que não haverá duplicidade de loja/conta para o mesmo método de pagamento
            $table->unique(['payment_method_id', 'loja_id', 'conta_corrente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_accounts');
    }
};
