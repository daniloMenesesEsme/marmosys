<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use App\Helpers\CompanyHelper;

class PdfHelper
{
    /**
     * Gera um PDF com o estilo padrão do sistema
     *
     * @param string $view Nome da view
     * @param array $data Dados para a view
     * @param string $filename Nome do arquivo para download
     * @param string $orientation Orientação do papel (portrait, landscape)
     * @param array $customOptions Opções personalizadas para o PDF
     * @return mixed
     */
    public static function generate($view, $data = [], $filename = 'relatorio.pdf', $orientation = 'portrait', $customOptions = [])
    {
        // Adicionar dados padrão para todos os relatórios
        $data['generated_at'] = now();
        $data['user_name'] = auth()->user()->name ?? 'Administrador';
        
        // Adicionar informações da empresa
        $data['company'] = CompanyHelper::getCompanyInfo();
        
        // Verificar diretórios necessários
        $tempDir = storage_path('app/pdf-temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Carregar view com os dados
        $html = View::make($view, $data)->render();
        
        // Adicionar cabeçalho de estilo CSS padrão
        $styleFile = public_path('css/pdf-reports.css');
        $style = '';
        
        if (file_exists($styleFile)) {
            $style = file_get_contents($styleFile);
        } else {
            // Carregar CSS do recurso se não existir no arquivo público
            if (file_exists(resource_path('css/pdf-reports.css'))) {
                $style = file_get_contents(resource_path('css/pdf-reports.css'));
                
                // Garantir que o diretório CSS existe
                if (!file_exists(public_path('css'))) {
                    mkdir(public_path('css'), 0755, true);
                }
                
                // Copiar para o diretório público para uso futuro
                file_put_contents($styleFile, $style);
            }
        }
        
        // Configurar o PDF
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>' . $style . '</style>' . $html;
        
        // Adicionar estilos para garantir que as imagens base64 funcionem
        $html = str_replace('<head>', '<head><style>img[src^="data:"] { max-width: 100%; }</style>', $html);
        
        // Opções padrão
        $defaultOptions = [
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'isJavascriptEnabled' => true,
            'dpi' => 120,
            'defaultEncoding' => 'UTF-8',
            'images' => true,
            'tempDir' => $tempDir,
            'chroot' => public_path(),
            'enable_php' => true,
            'enable_remote' => true,
        ];
        
        // Mesclar opções personalizadas com as padrão
        $options = array_merge($defaultOptions, $customOptions);
        
        // Carregar o PDF com as opções
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', $orientation);
        $pdf->setOptions($options);
        
        return $pdf->download($filename);
    }
} 