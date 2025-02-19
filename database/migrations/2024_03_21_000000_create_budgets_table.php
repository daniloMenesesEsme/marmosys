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
            $table->string('numero')->unique();
            $table->decimal('valor_total', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_final', 10, 2);
            $table->enum('status', ['rascunho', 'aguardando_aprovacao', 'aprovado', 'reprovado', 'convertido']);
            $table->date('data_validade');
            $table->text('observacoes')->nullable();
            $table->text('observacao')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->timestamps();
        });

        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained();
            $table->decimal('largura', 8, 2);
            $table->decimal('altura', 8, 2);
            $table->decimal('profundidade', 8, 2)->nullable();
            $table->integer('quantidade');
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained();
            $table->string('numero')->unique();
            $table->enum('status', ['aguardando_producao', 'em_producao', 'pronto_entrega', 'entregue']);
            $table->date('data_entrega')->nullable();
            $table->text('observacoes_producao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('observacao')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('budgets');
    }
}; 