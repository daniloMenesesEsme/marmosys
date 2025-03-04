<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run()
    {
        CompanySetting::create([
            'nome_empresa' => 'MarmoSys',
            'cnpj' => '00.000.000/0001-00',
            'endereco' => 'Endereço da Empresa',
            'telefone' => '(00) 0000-0000',
            'email' => 'contato@empresa.com',
            'observacoes_orcamento' => 'Orçamento válido por 15 dias.'
        ]);
    }
} 