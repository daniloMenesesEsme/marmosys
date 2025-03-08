<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        Client::create([
            'nome' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'telefone' => '(85) 99999-9999',
            'cpf_cnpj' => '123.456.789-00',
            'endereco' => 'Rua Teste, 123 - Fortaleza/CE',
            'ativo' => true
        ]);
    }
} 