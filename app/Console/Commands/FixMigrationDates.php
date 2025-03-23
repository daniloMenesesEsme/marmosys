<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class FixMigrationDates extends Command
{
    /**
     * O nome e a assinatura do comando do console
     *
     * @var string
     */
    protected $signature = 'migrations:fix-dates';

    /**
     * A descrição do comando do console
     *
     * @var string
     */
    protected $description = 'Corrige as datas das migrações, especialmente aquelas com datas futuras';

    /**
     * Execute o comando do console
     */
    public function handle()
    {
        $this->info('Verificando migrações...');
        $count = 0;
        $path = database_path('migrations');

        // Lista todos os arquivos de migração
        $files = File::glob($path . '/*.php');

        // Encontrar migrações duplicadas
        $duplicates = $this->findDuplicateMigrations($files);
        
        if (!empty($duplicates)) {
            $this->warn('Migrações duplicadas encontradas:');
            foreach ($duplicates as $name => $files) {
                $this->line(" - {$name}: " . count($files) . " arquivos");
                
                // Sugere manter apenas o arquivo mais recente
                usort($files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                
                $keep = $files[0];
                $this->info("   Recomendação: Manter {$keep}");
                
                for ($i = 1; $i < count($files); $i++) {
                    $this->warn("   Remover: {$files[$i]}");
                }
            }
            
            if (!$this->confirm('Deseja remover as migrações duplicadas mantendo a mais recente?', false)) {
                $this->info('Operação cancelada pelo usuário.');
                return;
            }
            
            foreach ($duplicates as $name => $files) {
                // Mantém o arquivo mais recente
                $keep = $files[0];
                
                for ($i = 1; $i < count($files); $i++) {
                    $this->warn("Removendo: {$files[$i]}");
                    File::delete($files[$i]);
                    $count++;
                }
            }
        }
        
        // Resetar contagem para migrações com datas futuras
        $count = 0;
        
        $now = Carbon::now();
        $datePrefix = $now->format('Y_m_d');
        $timestamp = $now->timestamp;

        foreach ($files as $file) {
            $filename = basename($file);
            
            // Verifica se a migração tem uma data futura (2025+)
            if (preg_match('/^(202[5-9]|203\d)_\d{2}_\d{2}_/', $filename)) {
                // Cria um novo nome de arquivo com a data atual + timestamp único
                $newFilename = $datePrefix . '_' . $timestamp . '_' . substr($filename, strpos($filename, '_', 11) + 1);
                $newPath = $path . '/' . $newFilename;
                
                $this->warn("Renomeando migração com data futura: {$filename} -> {$newFilename}");
                
                // Renomeia o arquivo
                File::move($file, $newPath);
                $count++;
                $timestamp++; // Incrementa para garantir ordem única
            }
        }
        
        if ($count > 0) {
            $this->info("{$count} migrações foram corrigidas.");
        } else {
            $this->info("Nenhuma migração com data futura encontrada.");
        }
    }
    
    /**
     * Encontra migrações duplicadas com base no nome da tabela
     */
    private function findDuplicateMigrations(array $files): array
    {
        $tables = [];
        $duplicates = [];
        
        foreach ($files as $file) {
            $filename = basename($file);
            
            // Extrai o nome da tabela
            preg_match('/(?:create|alter|update|add|fix)_(\w+)_table/', $filename, $matches);
            
            if (isset($matches[1])) {
                $tableName = $matches[1];
                
                if (str_contains($filename, 'create_' . $tableName . '_table')) {
                    if (!isset($tables[$tableName])) {
                        $tables[$tableName] = [];
                    }
                    
                    $tables[$tableName][] = $file;
                    
                    if (count($tables[$tableName]) > 1) {
                        $duplicates[$tableName] = $tables[$tableName];
                    }
                }
            }
        }
        
        return $duplicates;
    }
} 