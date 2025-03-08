use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBudgetItemsTable extends Migration
{
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            // Primeiro, remova qualquer foreign key existente
            $table->dropForeign(['material_id']);
            
            // Agora adicione a nova foreign key para products
            $table->foreign('material_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            
            // Restaura a foreign key original se necessário
            $table->foreign('material_id')
                  ->references('id')
                  ->on('budget_materials')
                  ->onDelete('restrict');
        });
    }
} 