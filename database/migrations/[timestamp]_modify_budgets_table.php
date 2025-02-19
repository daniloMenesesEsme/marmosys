<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('status')->default('aguardando_aprovacao');
            $table->boolean('convertido_pedido')->default(false);
            $table->foreignId('cliente_id')->constrained();
            $table->decimal('valor_total', 10, 2);
            $table->date('data_aprovacao')->nullable();
            $table->date('previsao_entrega')->nullable();
            $table->date('data_entrega')->nullable();
        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'convertido_pedido',
                'cliente_id',
                'valor_total',
                'data_aprovacao',
                'previsao_entrega',
                'data_entrega'
            ]);
        });
    }
}; 