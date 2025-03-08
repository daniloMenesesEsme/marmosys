use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class MigrateBudgetMaterialsToProducts extends Migration
{
    public function up()
    {
        // Primeiro, migra os dados
        $materials = DB::table('budget_materials')->get();
        
        foreach ($materials as $material) {
            DB::table('products')->updateOrInsert(
                ['codigo' => $material->codigo],
                [
                    'nome' => $material->nome,
                    'descricao' => $material->descricao,
                    'preco_venda' => $material->preco_venda,
                    'preco_custo' => $material->preco_custo,
                    'estoque_minimo' => $material->estoque_minimo,
                    'estoque_atual' => $material->estoque_atual,
                    'unidade_medida' => $material->unidade_medida,
                    'ativo' => $material->ativo,
                    'created_at' => $material->created_at,
                    'updated_at' => $material->updated_at
                ]
            );
        }

        // Atualiza as referências na tabela budget_items
        $items = DB::table('budget_items')
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
    }

    public function down()
    {
        // Não precisamos recriar a tabela no down() pois os dados já foram migrados
    }
} 