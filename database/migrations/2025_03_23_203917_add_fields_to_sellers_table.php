<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('nome', 100)->after('id');
            $table->string('cpf', 14)->nullable()->unique()->after('nome');
            $table->string('rg', 20)->nullable()->after('cpf');
            $table->string('telefone', 15)->nullable()->after('rg');
            $table->string('celular', 15)->nullable()->after('telefone');
            $table->string('email', 100)->nullable()->unique()->after('celular');
            $table->string('endereco', 200)->nullable()->after('email');
            $table->string('bairro', 100)->nullable()->after('endereco');
            $table->string('cidade', 100)->nullable()->after('bairro');
            $table->string('estado', 2)->nullable()->after('cidade');
            $table->string('cep', 10)->nullable()->after('estado');
            $table->string('numero', 10)->nullable()->after('cep');
            $table->string('complemento', 200)->nullable()->after('numero');
            $table->decimal('percentual_comissao', 5, 2)->default(0)->after('cep');
            $table->decimal('meta_mensal', 10, 2)->default(0)->after('percentual_comissao');
            $table->date('data_admissao')->nullable()->after('meta_mensal');
            $table->date('data_demissao')->nullable()->after('data_admissao');
            $table->text('observacoes')->nullable()->after('data_demissao');
            $table->boolean('ativo')->default(true)->after('observacoes');
            $table->unsignedBigInteger('created_by')->nullable()->after('ativo');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->softDeletes();
            
            // Relacionamentos
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'nome', 'cpf', 'rg', 'telefone', 'celular', 'email',
                'endereco', 'bairro', 'cidade', 'estado', 'cep', 'numero', 'complemento',
                'percentual_comissao', 'meta_mensal',
                'data_admissao', 'data_demissao', 'observacoes',
                'ativo', 'created_by', 'updated_by', 'deleted_at'
            ]);
        });
    }
};
