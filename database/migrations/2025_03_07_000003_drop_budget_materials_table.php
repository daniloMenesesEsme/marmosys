use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBudgetMaterialsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('budget_materials');
    }

    public function down()
    {
        // Não precisamos recriar a tabela no down() pois os dados já foram migrados
    }
} 