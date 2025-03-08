<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('rg')->nullable()->after('cpf_cnpj');
            $table->string('numero')->nullable()->after('endereco');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->nullable()->after('complemento');
            $table->string('cidade')->nullable()->after('bairro');
            $table->string('estado', 2)->nullable()->after('cidade');
            $table->string('cep', 9)->nullable()->after('estado');
            $table->text('observacoes')->nullable()->after('cep');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'rg',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado',
                'cep',
                'observacoes'
            ]);
        });
    }
}; 