<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $paymentMethods = [
            [
                'nome' => 'Dinheiro',
                'codigo' => 'DIN',
                'tipo' => 'dinheiro',
                'prazo' => 0,
                'taxa_padrao' => 0.00,
                'descricao' => 'Pagamento em dinheiro',
                'ativo' => true,
                'pagamento' => false,
                'parcelas_padrao' => 1,
                'envia_pdv' => true,
                'sangria_automatica' => true,
                'especie_pdv' => 'dinheiro',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'não',
                'controle_cartao' => false,
                'emite_comprovantes_vinculados' => false,
                'movimenta_conta_corrente' => true,
                'financial_agent_id' => null
            ],
            [
                'nome' => 'Cartão de Crédito',
                'codigo' => 'CCRED',
                'tipo' => 'cartao',
                'prazo' => 30,
                'taxa_padrao' => 2.99,
                'descricao' => 'Pagamento com cartão de crédito',
                'ativo' => true,
                'pagamento' => true,
                'parcelas_padrao' => 1,
                'envia_pdv' => true,
                'sangria_automatica' => false,
                'especie_pdv' => 'cartao',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'sim',
                'controle_cartao' => true,
                'emite_comprovantes_vinculados' => true,
                'movimenta_conta_corrente' => false,
                'financial_agent_id' => null
            ],
            [
                'nome' => 'Cartão de Débito',
                'codigo' => 'CDEB',
                'tipo' => 'cartao',
                'prazo' => 1,
                'taxa_padrao' => 1.99,
                'descricao' => 'Pagamento com cartão de débito',
                'ativo' => true,
                'pagamento' => false,
                'parcelas_padrao' => 1,
                'envia_pdv' => true,
                'sangria_automatica' => false,
                'especie_pdv' => 'cartao',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'sim',
                'controle_cartao' => true,
                'emite_comprovantes_vinculados' => true,
                'movimenta_conta_corrente' => false,
                'financial_agent_id' => null
            ],
            [
                'nome' => 'Boleto Bancário',
                'codigo' => 'BOL',
                'tipo' => 'boleto',
                'prazo' => 3,
                'taxa_padrao' => 0.00,
                'descricao' => 'Pagamento via boleto bancário',
                'ativo' => true,
                'pagamento' => false,
                'parcelas_padrao' => 1,
                'envia_pdv' => false,
                'sangria_automatica' => false,
                'especie_pdv' => 'outros',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'não',
                'controle_cartao' => false,
                'emite_comprovantes_vinculados' => false,
                'movimenta_conta_corrente' => true,
                'financial_agent_id' => null
            ],
            [
                'nome' => 'PIX',
                'codigo' => 'PIX',
                'tipo' => 'pix',
                'prazo' => 0,
                'taxa_padrao' => 0.00,
                'descricao' => 'Pagamento via PIX',
                'ativo' => true,
                'pagamento' => false,
                'parcelas_padrao' => 1,
                'envia_pdv' => true,
                'sangria_automatica' => false,
                'especie_pdv' => 'pix',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'não',
                'controle_cartao' => false,
                'emite_comprovantes_vinculados' => false,
                'movimenta_conta_corrente' => true,
                'financial_agent_id' => null
            ],
            [
                'nome' => 'Transferência',
                'codigo' => 'TRF',
                'tipo' => 'transferencia',
                'prazo' => 1,
                'taxa_padrao' => 0.00,
                'descricao' => 'Pagamento via transferência bancária',
                'ativo' => true,
                'pagamento' => false,
                'parcelas_padrao' => 1,
                'envia_pdv' => false,
                'sangria_automatica' => false,
                'especie_pdv' => 'outros',
                'tipo_cliente' => 'ambos',
                'pin_pad' => 'não',
                'controle_cartao' => false,
                'emite_comprovantes_vinculados' => false,
                'movimenta_conta_corrente' => true,
                'financial_agent_id' => null
            ]
        ];

        $now = Carbon::now();

        foreach ($paymentMethods as $method) {
            DB::table('payment_methods')->insert(array_merge($method, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
} 