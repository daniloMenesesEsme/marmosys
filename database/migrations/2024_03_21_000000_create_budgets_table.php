<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('budgets')) {
            Schema::create('budgets', function (Blueprint $table) {
                $table->id();
                // ... seus outros campos ...
                $table->text('observacao')->nullable();
                $table->foreignId('user_id')->constrained();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};