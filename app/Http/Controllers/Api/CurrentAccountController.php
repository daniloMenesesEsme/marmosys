<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrentAccount;
use Illuminate\Http\Request;

class CurrentAccountController extends Controller
{
    /**
     * Listar contas correntes por loja
     */
    public function getByStore(Request $request)
    {
        try {
            $storeId = $request->input('store_id');
            
            if (!$storeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID da loja não informado'
                ], 400);
            }
            
            $accounts = CurrentAccount::where('store_id', $storeId)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome', 'tipo', 'agencia', 'conta']);
            
            return response()->json([
                'success' => true,
                'data' => $accounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar contas correntes: ' . $e->getMessage()
            ], 500);
        }
    }
} 