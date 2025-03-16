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
            // Renomeia campos existentes
            $table->renameColumn('nome', 'descricao');
            $table->renameColumn('tipo', 'especie_documento');
            
            // Novos campos principais
            $table->string('codigo', 10)->after('id');
            $table->unsignedBigInteger('categoria_financeira_id')->nullable()->after('especie_documento');
            $table->string('agente')->nullable()->after('categoria_financeira_id');
            $table->boolean('controle_cartao')->default(false)->after('agente');
            $table->boolean('movimenta_conta_corrente')->default(false)->after('controle_cartao');
            $table->boolean('emite_comprovantes_vinculados')->default(false)->after('movimenta_conta_corrente');
            $table->boolean('envia_pdv')->default(false)->after('ativo');
            
            // Campos PDV
            $table->string('especie_pdv')->nullable();
            $table->boolean('pagamento')->default(false);
            $table->boolean('sangria_automatica')->default(false);
            $table->string('tipo_cliente')->nullable();
            $table->string('pin_pad')->nullable();
            $table->integer('prazo')->default(0);
            $table->string('identificador')->nullable();
            
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
            
            // Reverte renomeação de campos
            $table->renameColumn('descricao', 'nome');
            $table->renameColumn('especie_documento', 'tipo');
        });
    }
};
