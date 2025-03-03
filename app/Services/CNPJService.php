<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CNPJService
{
    protected $baseUrl = 'https://brasilapi.com.br/api/cnpj/v1/';

    public function find(string $cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        try {
            $response = Http::get($this->baseUrl . $cnpj);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'razao_social' => $data['razao_social'],
                    'nome_fantasia' => $data['nome_fantasia'],
                    'cnpj' => $data['cnpj'],
                    'email' => null,
                    'telefone' => null,
                    'cep' => $data['cep'],
                    'endereco' => $data['logradouro'],
                    'cidade' => $data['municipio'],
                    'estado' => $data['uf'],
                    'inscricao_estadual' => null
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
} 