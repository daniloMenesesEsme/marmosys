<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'rg')) {
                $table->string('rg')->nullable()->after('cpf_cnpj');
            }
            if (!Schema::hasColumn('clients', 'numero')) {
                $table->string('numero')->nullable()->after('endereco');
            }
            if (!Schema::hasColumn('clients', 'complemento')) {
                $table->string('complemento')->nullable()->after('numero');
            }
            if (!Schema::hasColumn('clients', 'bairro')) {
                $table->string('bairro')->nullable()->after('complemento');
            }
            if (!Schema::hasColumn('clients', 'cidade')) {
                $table->string('cidade')->nullable()->after('bairro');
            }
            if (!Schema::hasColumn('clients', 'estado')) {
                $table->string('estado', 2)->nullable()->after('cidade');
            }
            if (!Schema::hasColumn('clients', 'cep')) {
                $table->string('cep', 9)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('clients', 'observacoes')) {
                $table->text('observacoes')->nullable()->after('cep');
            }
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