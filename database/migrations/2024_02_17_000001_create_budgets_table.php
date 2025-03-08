<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->string('numero');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_final', 10, 2);
            $table->enum('status', ['rascunho', 'aguardando_aprovacao', 'aprovado', 'reprovado', 'convertido']);
            $table->date('data_validade');
            $table->text('observacoes')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}; 