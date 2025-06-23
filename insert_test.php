<?php

// Carregar o framework Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Importar as classes necessárias
use Illuminate\Support\Facades\DB;

// Inserir um registro de teste
$result = DB::table('regions')->insert([
    'name' => 'Região Teste',
    'description' => 'Descrição de teste',
    'state' => 'CE',
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now()
]);

if ($result) {
    echo "Registro inserido com sucesso!\n";
} else {
    echo "Falha ao inserir o registro.\n";
}

// Listar os registros da tabela
$regions = DB::table('regions')->get();
echo "Registros na tabela 'regions':\n";
foreach ($regions as $region) {
    echo "ID: {$region->id}, Nome: {$region->name}, Estado: {$region->state}\n";
}

echo "Operação concluída.\n"; 