<?php

namespace App\Helpers;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CompanyHelper
{
    /**
     * Obtém a instância da empresa principal
     *
     * @return Company|null
     */
    public static function getCompany()
    {
        return Company::first();
    }

    /**
     * Obtém o caminho completo do logo da empresa para uso em PDFs
     *
     * @return string|null
     */
    public static function getCompanyLogoPath()
    {
        $company = self::getCompany();
        
        if (!$company || empty($company->logo)) {
            return null;
        }
        
        // Caminho absoluto para o logo (para uso com DOMPDF)
        $logoPath = public_path('storage/' . $company->logo);
        
        if (File::exists($logoPath)) {
            return $logoPath;
        }
        
        return null;
    }

    /**
     * Obtém a URL do logo da empresa para uso em HTML
     *
     * @return string
     */
    public static function getCompanyLogoUrl()
    {
        $company = self::getCompany();
        
        if (!$company || empty($company->logo)) {
            // Retornar um logo padrão
            return asset('images/default-logo.png');
        }
        
        return asset('storage/' . $company->logo);
    }

    /**
     * Obtém o logo da empresa em base64 para uso em PDFs
     *
     * @return string
     */
    public static function getCompanyLogoBase64()
    {
        $logoPath = self::getCompanyLogoPath();
        
        if (!$logoPath) {
            // Usar um logo padrão incorporado
            return 'data:image/png;base64,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';
        }
        
        // Converter a imagem para base64
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    /**
     * Obtém informações da empresa formatadas para relatórios
     *
     * @return array
     */
    public static function getCompanyInfo()
    {
        $company = self::getCompany();
        
        if (!$company) {
            return [
                'name' => 'Sistema MarmoSys',
                'cnpj' => '',
                'address' => '',
                'phone' => '',
                'email' => '',
                'logo_base64' => self::getCompanyLogoBase64()
            ];
        }
        
        return [
            'name' => $company->nome_fantasia ?: $company->razao_social,
            'cnpj' => $company->formatted_cnpj,
            'address' => $company->endereco_completo,
            'phone' => $company->formatted_telefone,
            'email' => $company->email,
            'logo_base64' => self::getCompanyLogoBase64()
        ];
    }
} 