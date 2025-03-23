<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class VerifyModels extends Command
{
    /**
     * O nome e a assinatura do comando do console
     *
     * @var string
     */
    protected $signature = 'models:verify';

    /**
     * A descrição do comando do console
     *
     * @var string
     */
    protected $description = 'Verifica a integridade dos modelos comparando com as tabelas do banco de dados';

    /**
     * Execute o comando do console
     */
    public function handle()
    {
        $this->info('Verificando modelos...');
        
        // Lista todos os modelos
        $modelFiles = File::glob(app_path('Models/*.php'));
        
        $this->info('Encontrados ' . count($modelFiles) . ' modelos.');
        
        foreach ($modelFiles as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $fullClassName = "App\\Models\\{$className}";
            
            if (!class_exists($fullClassName)) {
                $this->warn("Classe '{$fullClassName}' não existe. Pulando...");
                continue;
            }
            
            $model = new $fullClassName();
            
            if (!method_exists($model, 'getTable')) {
                $this->warn("Modelo '{$className}' não é um Eloquent Model. Pulando...");
                continue;
            }
            
            $table = $model->getTable();
            
            if (!Schema::hasTable($table)) {
                $this->error("Tabela '{$table}' não existe para o modelo '{$className}'!");
                continue;
            }
            
            $this->line("Verificando modelo '{$className}' (tabela: {$table})...");
            
            // Verifica os campos fillable
            $this->verifyFillable($model, $table);
            
            // Verifica os casts
            $this->verifyCasts($model, $table);
            
            $this->line("");
        }
        
        $this->info('Verificação concluída.');
    }
    
    /**
     * Verifica se os campos fillable correspondem às colunas da tabela
     */
    private function verifyFillable($model, $table)
    {
        $fillable = $model->getFillable();
        $columns = Schema::getColumnListing($table);
        
        // Remove colunas padrão que não precisam estar em fillable
        $defaultColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $columns = array_diff($columns, $defaultColumns);
        
        // Verifica se há colunas ausentes em fillable
        $missing = array_diff($columns, $fillable);
        
        if (!empty($missing)) {
            $this->warn("  Colunas ausentes em fillable: " . implode(', ', $missing));
            
            $this->info("  Sugestão de fillable:");
            $combinedFillable = array_unique(array_merge($fillable, $missing));
            sort($combinedFillable);
            
            $fillableCode = "protected \$fillable = [\n";
            foreach ($combinedFillable as $field) {
                $fillableCode .= "        '{$field}',\n";
            }
            $fillableCode .= "    ];";
            
            $this->line($fillableCode);
        } else {
            $this->info("  Fillable OK");
        }
        
        // Verifica se há campos em fillable que não existem na tabela
        $nonExistent = array_diff($fillable, $columns);
        
        if (!empty($nonExistent)) {
            $this->error("  Campos em fillable que não existem na tabela: " . implode(', ', $nonExistent));
        }
    }
    
    /**
     * Verifica se os casts correspondem aos tipos de colunas
     */
    private function verifyCasts($model, $table)
    {
        $casts = $model->getCasts();
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        $suggestedCasts = [];
        
        foreach ($columns as $column) {
            $name = $column->Field;
            $type = $column->Type;
            
            // Ignora colunas padrão que não precisam de cast
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            
            // Determina o cast apropriado com base no tipo da coluna
            $suggestedCast = null;
            
            if (preg_match('/^tinyint\(1\)$/', $type)) {
                $suggestedCast = 'boolean';
            } elseif (preg_match('/^int/', $type)) {
                $suggestedCast = 'integer';
            } elseif (preg_match('/^decimal/', $type)) {
                if (preg_match('/decimal\((\d+),(\d+)\)/', $type, $matches)) {
                    $scale = $matches[2];
                    $suggestedCast = 'decimal:' . $scale;
                } else {
                    $suggestedCast = 'decimal:2';
                }
            } elseif (preg_match('/^(date)$/', $type)) {
                $suggestedCast = 'date';
            } elseif (preg_match('/^(datetime|timestamp)/', $type)) {
                $suggestedCast = 'datetime';
            } elseif (preg_match('/^json$/', $type)) {
                $suggestedCast = 'array';
            }
            
            if ($suggestedCast) {
                $currentCast = $casts[$name] ?? null;
                
                if ($currentCast !== $suggestedCast) {
                    $suggestedCasts[$name] = $suggestedCast;
                }
            }
        }
        
        if (!empty($suggestedCasts)) {
            $this->warn("  Sugestões de casts:");
            
            // Mescla os casts existentes com os sugeridos
            $combinedCasts = array_merge($casts, $suggestedCasts);
            ksort($combinedCasts);
            
            $castsCode = "protected \$casts = [\n";
            foreach ($combinedCasts as $field => $cast) {
                $castsCode .= "        '{$field}' => '{$cast}',\n";
            }
            $castsCode .= "    ];";
            
            $this->line($castsCode);
        } else {
            $this->info("  Casts OK");
        }
    }
} 