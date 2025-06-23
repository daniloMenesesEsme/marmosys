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

// Checar se a tabela já existe
if (!Schema::hasTable('regions')) {
    echo "Criando tabela 'regions'...\n";
    
    Schema::create('regions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('state', 2);
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
    
    echo "Tabela 'regions' criada com sucesso!\n";
} else {
    echo "A tabela 'regions' já existe. Verificando sua estrutura...\n";
    
    // Se a tabela existe, verificar se tem todas as colunas necessárias
    $columns = DB::select('SHOW COLUMNS FROM regions');
    $columnNames = array_map(function($column) {
        return $column->Field;
    }, $columns);
    
    echo "Colunas existentes: " . implode(', ', $columnNames) . "\n";
}

echo "Operação concluída.\n"; 