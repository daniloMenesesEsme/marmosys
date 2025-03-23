<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('room_items')) {
            Schema::create('room_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained()->onDelete('cascade');
                $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
                $table->decimal('largura', 10, 2)->nullable();
                $table->decimal('altura', 10, 2)->nullable();
                $table->decimal('profundidade', 10, 2)->nullable();
                $table->decimal('quantidade', 10, 2);
                $table->decimal('valor_unitario', 10, 2);
                $table->decimal('valor_total', 10, 2);
                $table->text('observacoes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('room_items');
    }
}; 