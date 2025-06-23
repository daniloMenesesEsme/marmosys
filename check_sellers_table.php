<?php

// Carregar o framework Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Importar as classes necessárias
use Illuminate\Support\Facades\DB;

// Verificar a estrutura da tabela sellers
echo "Verificando estrutura da tabela 'sellers'...\n";
$columns = DB::select('SHOW COLUMNS FROM sellers');

echo "Colunas da tabela 'sellers':\n";
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\nOperação concluída.\n"; 