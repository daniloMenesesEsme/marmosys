<?php

require __DIR__ . '/vendor/autoload.php';

// Carregar o aplicativo Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Definir configurações do banco de dados
$db_config = config('database.connections.mysql');
$db_host = $db_config['host'];
$db_name = $db_config['database'];
$db_user = $db_config['username'];
$db_pass = $db_config['password'];

// Definir caminho do backup
$backup_dir = storage_path('app/backups');
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$filename = 'pre_implementation_backup_' . date('Y-m-d_His') . '.sql';
$backup_file = $backup_dir . '/' . $filename;

// Criar comando para backup (Windows)
$cmd = sprintf(
    '"%s" --host=%s --user=%s --password=%s %s > "%s"',
    'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
    escapeshellarg($db_host),
    escapeshellarg($db_user),
    escapeshellarg($db_pass),
    escapeshellarg($db_name),
    $backup_file
);

echo "Iniciando backup do banco de dados...\n";
system($cmd, $return_var);

if ($return_var === 0) {
    echo "Backup concluído com sucesso!\n";
    echo "Arquivo: $filename\n";
    echo "Localização: $backup_file\n";
} else {
    echo "Erro ao criar backup (código: $return_var)\n";
} 