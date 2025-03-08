<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->decimal('quantidade', 10, 2);
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_items');
    }
}; 