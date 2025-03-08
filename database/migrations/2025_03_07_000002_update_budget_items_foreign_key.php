use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBudgetItemsForeignKey extends Migration
{
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            // Remove a foreign key antiga
            $table->dropForeign(['material_id']);
            
            // Adiciona a nova foreign key para products
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
            
            $table->foreign('material_id')
                  ->references('id')
                  ->on('budget_materials')
                  ->onDelete('restrict');
        });
    }
} 