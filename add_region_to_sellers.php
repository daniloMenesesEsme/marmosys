<?php

// Carregar o framework Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Importar as classes necessárias
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// Verificar se a coluna region_id já existe na tabela sellers
$hasColumn = Schema::hasColumn('sellers', 'region_id');

if (!$hasColumn) {
    echo "Adicionando coluna 'region_id' à tabela 'sellers'...\n";
    
    Schema::table('sellers', function (Blueprint $table) {
        $table->unsignedBigInteger('region_id')->nullable()->after('status');
        $table->foreign('region_id')->references('id')->on('regions');
    });
    
    echo "Coluna 'region_id' adicionada com sucesso à tabela 'sellers'!\n";
} else {
    echo "A coluna 'region_id' já existe na tabela 'sellers'.\n";
}

// Verificar a estrutura da tabela sellers
$columns = DB::select('SHOW COLUMNS FROM sellers');
$columnNames = array_map(function($column) {
    return $column->Field;
}, $columns);

echo "Colunas da tabela 'sellers': " . implode(', ', $columnNames) . "\n";

echo "Operação concluída.\n"; 