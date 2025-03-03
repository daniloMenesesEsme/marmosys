<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run()
    {
        CompanySetting::create([
            'nome_empresa' => 'Angular Granitos - Fábrica',
            'cnpj' => '29.123.952/0001-84',
            'telefone' => '85 9 9915-2076',
            'email' => 'angulargranitos@outlook.com',
            'endereco' => 'RUA QUINTINO CUNHA, 2950 - CAUCAIA - CE',
        ]);
    }
} 