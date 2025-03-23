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
     * @return mixed
     */
    public static function generate($view, $data = [], $filename = 'relatorio.pdf', $orientation = 'portrait')
    {
        // Adicionar dados padrão para todos os relatórios
        $data['generated_at'] = now();
        $data['user_name'] = auth()->user()->name ?? 'Administrador';
        
        // Adicionar informações da empresa
        $data['company'] = CompanyHelper::getCompanyInfo();
        
        // Carregar view com os dados
        $html = View::make($view, $data)->render();
        
        // Adicionar cabeçalho de estilo CSS padrão
        $styleFile = public_path('css/pdf-reports.css');
        $style = '';
        
        if (file_exists($styleFile)) {
            $style = file_get_contents($styleFile);
        } else {
            // Carregar CSS do recurso se não existir no arquivo público
            $style = file_get_contents(resource_path('css/pdf-reports.css'));
            
            // Garantir que o diretório CSS existe
            if (!file_exists(public_path('css'))) {
                mkdir(public_path('css'), 0755, true);
            }
            
            // Copiar para o diretório público para uso futuro
            file_put_contents($styleFile, $style);
        }
        
        // Configurar o PDF
        $pdf = Pdf::loadHTML('<style>' . $style . '</style>' . $html);
        $pdf->setPaper('a4', $orientation);
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'dpi' => 120
        ]);
        
        return $pdf->download($filename);
    }
} 