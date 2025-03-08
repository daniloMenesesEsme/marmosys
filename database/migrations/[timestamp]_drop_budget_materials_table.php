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
        Schema::create('budget_materials', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco_venda', 10, 2);
            $table->decimal('preco_custo', 10, 2);
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->decimal('estoque_atual', 10, 2)->default(0);
            $table->string('unidade_medida');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }
} 