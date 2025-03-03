<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::query()
            ->orderBy('razao_social')
            ->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function findByCNPJ($cnpj)
    {
        try {
            // Remove caracteres especiais do CNPJ
            $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
            
            // Faz a requisição para a API
            $response = Http::get("https://publica.cnpj.ws/cnpj/{$cnpj}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'razao_social' => $data['razao_social'] ?? '',
                        'nome_fantasia' => $data['estabelecimento']['nome_fantasia'] ?? '',
                        'inscricao_estadual' => '', // API não fornece
                        'endereco' => [
                            'logradouro' => $data['estabelecimento']['logradouro'] ?? '',
                            'numero' => $data['estabelecimento']['numero'] ?? '',
                            'complemento' => $data['estabelecimento']['complemento'] ?? '',
                            'bairro' => $data['estabelecimento']['bairro'] ?? '',
                            'municipio' => $data['estabelecimento']['cidade']['nome'] ?? '',
                            'uf' => $data['estabelecimento']['estado']['sigla'] ?? '',
                            'cep' => $data['estabelecimento']['cep'] ?? ''
                        ],
                        'telefone' => $data['estabelecimento']['ddd1'] . $data['estabelecimento']['telefone1'] ?? '',
                        'email' => $data['estabelecimento']['email'] ?? ''
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'CNPJ não encontrado'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar CNPJ'
            ], 500);
        }
    }
} 