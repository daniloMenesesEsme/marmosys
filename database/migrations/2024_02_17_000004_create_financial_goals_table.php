<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_goals', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor_meta', 10, 2);
            $table->decimal('valor_atual', 10, 2)->default(0);
            $table->date('data_inicial');
            $table->date('data_final');
            $table->enum('status', ['em_andamento', 'concluida', 'cancelada'])->default('em_andamento');
            $table->text('observacoes')->nullable();
            $table->decimal('percentual', 5, 2)->default(0); // Percentual de conclusão
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_goals');
    }
}; 