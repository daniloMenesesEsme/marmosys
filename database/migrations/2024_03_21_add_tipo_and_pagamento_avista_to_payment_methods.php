<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->enum('tipo', ['dinheiro', 'pix', 'cartao', 'boleto', 'outros'])->after('nome')->default('outros');
            $table->boolean('pagamento_avista')->after('tipo')->default(false);
        });

        // Atualiza registros existentes
        DB::table('payment_methods')->where('nome', 'like', '%Dinheiro%')->update([
            'tipo' => 'dinheiro',
            'pagamento_avista' => true,
            'parcelas_padrao' => 1,
            'taxa_padrao' => 0
        ]);

        DB::table('payment_methods')->where('nome', 'like', '%PIX%')->update([
            'tipo' => 'pix',
            'pagamento_avista' => true,
            'parcelas_padrao' => 1,
            'taxa_padrao' => 0
        ]);
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'pagamento_avista']);
        });
    }
}; 