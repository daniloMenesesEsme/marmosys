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
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'tipo')) {
                $table->string('tipo', 20)->after('nome');
            }
            if (!Schema::hasColumn('payment_methods', 'codigo')) {
                $table->string('codigo', 10)->after('id');
            }
            if (!Schema::hasColumn('payment_methods', 'categoria_financeira_id')) {
                $table->foreignId('categoria_financeira_id')->nullable()->after('tipo');
            }
            if (!Schema::hasColumn('payment_methods', 'agente')) {
                $table->string('agente')->nullable()->after('categoria_financeira_id');
            }
            if (!Schema::hasColumn('payment_methods', 'controle_cartao')) {
                $table->boolean('controle_cartao')->default(false)->after('agente');
            }
            if (!Schema::hasColumn('payment_methods', 'movimenta_conta_corrente')) {
                $table->boolean('movimenta_conta_corrente')->default(false)->after('controle_cartao');
            }
            if (!Schema::hasColumn('payment_methods', 'emite_comprovantes_vinculados')) {
                $table->boolean('emite_comprovantes_vinculados')->default(false)->after('movimenta_conta_corrente');
            }
            if (!Schema::hasColumn('payment_methods', 'envia_pdv')) {
                $table->boolean('envia_pdv')->default(false)->after('ativo');
            }
            if (!Schema::hasColumn('payment_methods', 'especie_pdv')) {
                $table->string('especie_pdv')->nullable();
            }
            if (!Schema::hasColumn('payment_methods', 'pagamento')) {
                $table->boolean('pagamento')->default(false);
            }
            if (!Schema::hasColumn('payment_methods', 'sangria_automatica')) {
                $table->boolean('sangria_automatica')->default(false);
            }
            if (!Schema::hasColumn('payment_methods', 'tipo_cliente')) {
                $table->string('tipo_cliente')->nullable();
            }
            if (!Schema::hasColumn('payment_methods', 'pin_pad')) {
                $table->string('pin_pad')->nullable();
            }
            if (!Schema::hasColumn('payment_methods', 'prazo')) {
                $table->integer('prazo')->default(0);
            }
            if (!Schema::hasColumn('payment_methods', 'identificador')) {
                $table->string('identificador')->nullable();
            }
            
            // Foreign key
            $table->foreign('categoria_financeira_id')
                  ->references('id')
                  ->on('financial_categories')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Remove foreign key
            $table->dropForeign(['categoria_financeira_id']);
            
            // Remove campos PDV
            $table->dropColumn([
                'tipo',
                'codigo',
                'categoria_financeira_id',
                'agente',
                'controle_cartao',
                'movimenta_conta_corrente',
                'emite_comprovantes_vinculados',
                'envia_pdv',
                'especie_pdv',
                'pagamento',
                'sangria_automatica',
                'tipo_cliente',
                'pin_pad',
                'prazo',
                'identificador'
            ]);
        });
    }
};
