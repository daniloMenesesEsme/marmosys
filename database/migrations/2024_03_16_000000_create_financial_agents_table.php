<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_agents', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('codigo', 20)->unique();
            $table->enum('tipo', ['banco', 'financeira', 'outros']);
            $table->boolean('status')->default(true);
            $table->foreignId('financial_category_id')->nullable()->constrained('financial_categories')->onDelete('set null');
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->onDelete('set null');
            $table->string('codigo_banco', 3)->nullable();
            $table->string('agencia', 10)->nullable();
            $table->string('conta', 20)->nullable();
            $table->string('digito', 2)->nullable();
            $table->string('cnpj', 14)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_agents');
    }
}; 