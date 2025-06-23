<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('regions')) {
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
    echo "A tabela 'regions' já existe.\n";
}

echo "Script concluído.\n"; 