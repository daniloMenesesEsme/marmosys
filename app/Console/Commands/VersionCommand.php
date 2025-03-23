<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class VersionCommand extends Command
{
    /**
     * O nome e a assinatura do comando.
     *
     * @var string
     */
    protected $signature = 'version {action=show : Ação a ser executada (show, bump:major, bump:minor, bump:patch)}';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Gerencia a versão do sistema';

    /**
     * Executa o comando.
     */
    public function handle()
    {
        $action = $this->argument('action');
        
        // Carregar configuração atual
        $versionConfig = config('version');
        $currentVersion = $versionConfig['number'];
        
        // Extrair componentes da versão
        preg_match('/v(\d+)\.(\d+)\.(\d+)/', $currentVersion, $matches);
        $major = (int)$matches[1];
        $minor = (int)$matches[2];
        $patch = (int)$matches[3];
        
        // Processar ação
        switch ($action) {
            case 'show':
                $this->info("Versão atual: $currentVersion");
                $this->info("Nome: {$versionConfig['name']}");
                $this->info("Data de lançamento: {$versionConfig['release_date']}");
                break;
                
            case 'bump:major':
                $major++;
                $minor = 0;
                $patch = 0;
                $this->updateVersion($major, $minor, $patch);
                break;
                
            case 'bump:minor':
                $minor++;
                $patch = 0;
                $this->updateVersion($major, $minor, $patch);
                break;
                
            case 'bump:patch':
                $patch++;
                $this->updateVersion($major, $minor, $patch);
                break;
                
            default:
                $this->error("Ação '$action' não reconhecida");
                return 1;
        }
        
        return 0;
    }
    
    /**
     * Atualiza o arquivo de versão com os novos valores.
     */
    protected function updateVersion($major, $minor, $patch)
    {
        $newVersion = "v$major.$minor.$patch";
        $versionFile = config_path('version.php');
        $content = File::get($versionFile);
        
        // Atualizar número da versão
        $content = preg_replace(
            "/'number' => '.*?'/", 
            "'number' => '$newVersion'", 
            $content
        );
        
        // Atualizar data de lançamento
        $today = date('Y-m-d');
        $content = preg_replace(
            "/'release_date' => '.*?'/", 
            "'release_date' => '$today'", 
            $content
        );
        
        // Salvar alterações
        File::put($versionFile, $content);
        
        $this->info("Versão atualizada para: $newVersion");
        $this->info("Data de lançamento: $today");
        
        // Sugestão para criar tag git
        $this->info("");
        $this->info("Para criar uma tag Git, execute:");
        $this->info("git tag -a $newVersion -m \"Release $newVersion\"");
        $this->info("git push origin $newVersion");
    }
} 