<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('budget_materials')) {
            // Primeiro, migra os dados
            $materials = DB::table('budget_materials')->get();
            
            foreach ($materials as $material) {
                DB::table('products')->updateOrInsert(
                    ['codigo' => $material->codigo],
                    [
                        'nome' => $material->nome,
                        'descricao' => $material->descricao ?? null,
                        'preco_venda' => $material->preco ?? 0,
                        'preco_custo' => $material->preco_custo ?? 0,
                        'estoque_minimo' => $material->estoque_minimo ?? 0,
                        'estoque_atual' => $material->estoque_atual ?? 0,
                        'unidade_medida' => $material->unidade_medida ?? 'm²',
                        'ativo' => $material->ativo ?? true,
                        'tipo' => 'material',
                        'created_at' => $material->created_at,
                        'updated_at' => $material->updated_at
                    ]
                );
            }

            // Atualiza as referências na tabela budget_items
            $items = DB::table('budget_items')
                ->select('budget_items.*', 'budget_materials.codigo')
                ->join('budget_materials', 'budget_items.material_id', '=', 'budget_materials.id')
                ->get();
                
            foreach ($items as $item) {
                $product = DB::table('products')
                    ->where('codigo', $item->codigo)
                    ->first();
                    
                if ($product) {
                    DB::table('budget_items')
                        ->where('id', $item->id)
                        ->update(['material_id' => $product->id]);
                }
            }

            // Atualiza a foreign key
            Schema::table('budget_items', function (Blueprint $table) {
                $table->dropForeign(['material_id']);
                
                $table->foreign('material_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('restrict');
            });

            // Por fim, dropa a tabela budget_materials
            Schema::dropIfExists('budget_materials');
        }
    }

    public function down()
    {
        // Não precisamos recriar a tabela no down() pois os dados já foram migrados
    }
}; 