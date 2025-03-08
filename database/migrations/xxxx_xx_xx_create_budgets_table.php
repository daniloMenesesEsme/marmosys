use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->date('data');
            $table->date('previsao_entrega');
            $table->unsignedBigInteger('client_id');
            $table->string('status');
            $table->decimal('valor_total', 8, 2);
            $table->decimal('desconto', 8, 2);
            $table->decimal('valor_final', 8, 2);
            $table->date('data_validade');
            $table->text('observacoes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
} 