<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DiagnosticController extends Controller
{
    public function index()
    {
        $results = [
            'database' => $this->checkDatabase(),
            'tables' => $this->checkTables(),
            'models' => $this->checkModels(),
            'migrations' => $this->checkMigrations(),
            'logs' => $this->getRecentErrors(),
            'routes' => $this->checkRoutes(),
            'info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido',
                'system' => php_uname(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'post_max_size' => ini_get('post_max_size'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
            ]
        ];
        
        return view('diagnostic.index', compact('results'));
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            
            return [
                'status' => 'success',
                'message' => 'Conexão com o banco de dados OK',
                'details' => [
                    'name' => DB::connection()->getDatabaseName(),
                    'driver' => DB::connection()->getDriverName(),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro na conexão com o banco de dados',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkTables()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $tableName = 'Tables_in_' . DB::connection()->getDatabaseName();
            
            $tableInfo = [];
            foreach ($tables as $table) {
                $name = $table->$tableName;
                $columns = Schema::getColumnListing($name);
                $count = DB::table($name)->count();
                
                $tableInfo[$name] = [
                    'columns' => count($columns),
                    'records' => $count,
                    'has_id' => in_array('id', $columns),
                    'has_timestamps' => in_array('created_at', $columns) && in_array('updated_at', $columns),
                    'has_soft_deletes' => in_array('deleted_at', $columns),
                ];
            }
            
            return [
                'status' => 'success',
                'count' => count($tables),
                'details' => $tableInfo
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao verificar tabelas',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkModels()
    {
        try {
            $modelFiles = File::glob(app_path('Models/*.php'));
            $modelInfo = [];
            
            foreach ($modelFiles as $file) {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $fullClassName = "App\\Models\\{$className}";
                
                try {
                    if (!class_exists($fullClassName)) {
                        $modelInfo[$className] = [
                            'status' => 'error',
                            'message' => 'Classe não existe'
                        ];
                        continue;
                    }
                    
                    $model = new $fullClassName();
                    
                    if (!method_exists($model, 'getTable')) {
                        $modelInfo[$className] = [
                            'status' => 'warning',
                            'message' => 'Não é um modelo Eloquent'
                        ];
                        continue;
                    }
                    
                    $table = $model->getTable();
                    $tableExists = Schema::hasTable($table);
                    
                    $modelInfo[$className] = [
                        'status' => $tableExists ? 'success' : 'error',
                        'table' => $table,
                        'table_exists' => $tableExists,
                        'fillable' => $model->getFillable(),
                        'casts' => $model->getCasts(),
                    ];
                } catch (\Exception $modelException) {
                    $modelInfo[$className] = [
                        'status' => 'error',
                        'message' => 'Erro ao instanciar modelo: ' . $modelException->getMessage()
                    ];
                }
            }
            
            return [
                'status' => 'success',
                'count' => count($modelFiles),
                'details' => $modelInfo
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao verificar modelos',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkMigrations()
    {
        try {
            $files = File::glob(database_path('migrations/*.php'));
            $migrationInfo = [];
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Extrai data e nome
                preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})_(.+)\.php$/', $filename, $matches);
                
                if (count($matches) >= 3) {
                    $date = $matches[1];
                    $name = $matches[2];
                    
                    // Verifica se a data está no futuro
                    $isFutureDate = strpos($filename, '202') === 0 && substr($filename, 0, 4) > date('Y');
                    
                    $migrationInfo[$filename] = [
                        'date' => $date,
                        'name' => $name,
                        'future_date' => $isFutureDate,
                        'file_size' => File::size($file),
                        'modified' => date('Y-m-d H:i:s', File::lastModified($file)),
                    ];
                } else {
                    $migrationInfo[$filename] = [
                        'status' => 'warning',
                        'message' => 'Formato de nome inválido'
                    ];
                }
            }
            
            // Verifica duplicações de tabelas
            $tables = [];
            $duplicates = [];
            
            foreach ($migrationInfo as $filename => $info) {
                if (isset($info['name'])) {
                    $name = $info['name'];
                    
                    // Extrai o nome da tabela
                    preg_match('/(?:create|alter|update|add|fix)_(\w+)_table/', $name, $matches);
                    
                    if (isset($matches[1])) {
                        $tableName = $matches[1];
                        
                        if (str_contains($name, 'create_' . $tableName . '_table')) {
                            if (!isset($tables[$tableName])) {
                                $tables[$tableName] = [];
                            }
                            
                            $tables[$tableName][] = $filename;
                            
                            if (count($tables[$tableName]) > 1) {
                                $duplicates[$tableName] = $tables[$tableName];
                            }
                        }
                    }
                }
            }
            
            return [
                'status' => !empty($duplicates) ? 'warning' : 'success',
                'count' => count($files),
                'duplicates' => $duplicates,
                'details' => $migrationInfo
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao verificar migrações',
                'details' => $e->getMessage()
            ];
        }
    }

    private function getRecentErrors($lines = 100)
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!File::exists($logFile)) {
                return [
                    'status' => 'warning',
                    'message' => 'Arquivo de log não encontrado'
                ];
            }
            
            $logSize = File::size($logFile);
            
            // Se o arquivo for muito grande, pegue apenas o final
            if ($logSize > 1024 * 1024) { // Se maior que 1MB
                $content = exec("tail -n {$lines} {$logFile}");
            } else {
                $content = File::get($logFile);
            }
            
            // Extrai entradas de erro
            $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$)/s';
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
            
            $errors = [];
            foreach ($matches as $match) {
                if ($match[1] === 'local' && $match[2] === 'ERROR') {
                    $errors[] = [
                        'timestamp' => substr($match[0], 1, 19),
                        'message' => $match[3]
                    ];
                }
            }
            
            return [
                'status' => 'success',
                'count' => count($errors),
                'details' => array_slice($errors, -20) // Mostra apenas os 20 erros mais recentes
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao acessar logs',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkRoutes()
    {
        try {
            $routeCollection = app('router')->getRoutes();
            $routes = [];
            
            foreach ($routeCollection as $route) {
                $action = $route->getAction();
                $controller = $action['controller'] ?? 'Closure';
                
                if (!isset($routes[$controller])) {
                    $routes[$controller] = [];
                }
                
                $routes[$controller][] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'middleware' => $action['middleware'] ?? []
                ];
            }
            
            return [
                'status' => 'success',
                'count' => count($routeCollection),
                'details' => $routes
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao verificar rotas',
                'details' => $e->getMessage()
            ];
        }
    }

    public function fix(Request $request)
    {
        $type = $request->input('type');
        $result = [];
        
        switch ($type) {
            case 'migrations':
                Artisan::call('migrations:fix-dates');
                $result = [
                    'status' => 'success',
                    'message' => 'Comando para corrigir migrações executado',
                    'output' => Artisan::output()
                ];
                break;
                
            case 'models':
                Artisan::call('models:verify');
                $result = [
                    'status' => 'success',
                    'message' => 'Comando para verificar modelos executado',
                    'output' => Artisan::output()
                ];
                break;
                
            case 'cache':
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                $result = [
                    'status' => 'success',
                    'message' => 'Cache limpo com sucesso'
                ];
                break;
                
            case 'logs':
                $logFile = storage_path('logs/laravel.log');
                if (File::exists($logFile)) {
                    File::put($logFile, '');
                }
                $result = [
                    'status' => 'success',
                    'message' => 'Arquivo de logs limpo'
                ];
                break;
                
            default:
                $result = [
                    'status' => 'error',
                    'message' => 'Tipo de correção inválido'
                ];
        }
        
        return response()->json($result);
    }
} 