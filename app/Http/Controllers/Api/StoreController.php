<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Listar todas as lojas ativas
     */
    public function list()
    {
        try {
            $stores = Store::where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome', 'codigo']);

            return response()->json([
                'success' => true,
                'data' => $stores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar lojas: ' . $e->getMessage()
            ], 500);
        }
    }
}