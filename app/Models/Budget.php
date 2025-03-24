<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Order;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'data',
        'previsao_entrega',
        'client_id',
        'seller_id',
        'status',
        'valor_total',
        'desconto',
        'valor_final',
        'data_validade',
        'observacoes',
        'user_id',
        'motivo_reprovacao',
        'approved_by',
        'approved_at',
        'payment_method_id',
        'payment_condition',
        'payment_installments',
        'payment_fee',
        'first_installment_date',
        'converted_to_receivable'
    ];

    protected $casts = [
        'data' => 'date',
        'previsao_entrega' => 'date',
        'data_validade' => 'date',
        'approved_at' => 'datetime',
        'first_installment_date' => 'date',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'converted_to_receivable' => 'boolean'
    ];

    protected $dates = [
        'data',
        'previsao_entrega',
        'data_validade',
        'approved_at'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function rooms()
    {
        return $this->hasMany(BudgetRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function installments()
    {
        return $this->hasMany(BudgetInstallment::class);
    }

    public function getStatusTextAttribute()
    {
        return [
            'rascunho' => 'Rascunho',
            'aguardando_aprovacao' => 'Aguardando Aprovação',
            'aprovado' => 'Aprovado',
            'reprovado' => 'Reprovado',
            'convertido' => 'Convertido em Pedido'
        ][$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute()
    {
        return [
            'rascunho' => 'grey',
            'aguardando_aprovacao' => 'orange',
            'aprovado' => 'green',
            'reprovado' => 'red',
            'convertido' => 'blue'
        ][$this->status] ?? 'grey';
    }

    public function recalcularTotal()
    {
        $this->valor_total = $this->rooms->sum('valor_total');
        $this->valor_final = $this->valor_total - ($this->desconto ?? 0);
        $this->save();
    }

    public function converterEmPedido()
    {
        if ($this->status !== 'aprovado') {
            throw new \Exception('Apenas orçamentos aprovados podem ser convertidos em pedidos.');
        }

        $order = Order::create([
            'budget_id' => $this->id,
            'numero' => 'PED-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT),
            'status' => 'aguardando_producao'
        ]);

        $this->update(['status' => 'convertido']);

        return $order;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            if (!$budget->numero) {
                $ano = date('Y');
                $ultimoNumero = static::whereYear('created_at', $ano)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $sequencial = 1;
                if ($ultimoNumero) {
                    preg_match('/ORC-\d+(\d{4})$/', $ultimoNumero->numero, $matches);
                    $sequencial = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
                }
                
                $budget->numero = 'ORC-' . $ano . str_pad($sequencial, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function calcularRT()
    {
        $valorRT = 0;
        
        foreach ($this->rooms as $room) {
            foreach ($room->items as $item) {
                if (!$item->aplicar_rt) continue;

                if ($this->rt_material && $item->tipo === 'produto_fabricado') {
                    $valorRT += $item->valor_total * ($this->rt_percentual / 100);
                }
                
                if ($this->rt_colocacao && $item->valor_mao_obra_colocacao > 0) {
                    $valorRT += $item->valor_mao_obra_colocacao * ($this->rt_percentual / 100);
                }
                
                if ($this->rt_produto && $item->tipo === 'produto_revendido') {
                    $valorRT += $item->valor_total * ($this->rt_percentual / 100);
                }
            }
        }

        if ($this->rt_frete && $this->valor_frete > 0) {
            $valorRT += $this->valor_frete * ($this->rt_percentual / 100);
        }

        return $valorRT;
    }

    /**
     * Gera as parcelas do orçamento com base nos dados de pagamento
     */
    public function generateInstallments()
    {
        // Remove parcelas existentes
        $this->installments()->delete();
        
        if (!$this->payment_method_id || !$this->first_installment_date || $this->payment_installments < 1) {
            return false;
        }

        $valorParcela = $this->valor_final / $this->payment_installments;
        $dataVencimento = \Carbon\Carbon::parse($this->first_installment_date);
        
        for ($i = 1; $i <= $this->payment_installments; $i++) {
            $this->installments()->create([
                'numero_parcela' => $i,
                'valor' => $valorParcela,
                'data_vencimento' => $dataVencimento->copy()
            ]);
            
            // Avança para o próximo mês
            $dataVencimento->addMonth();
        }
        
        return true;
    }

    /**
     * Converte as parcelas do orçamento em contas a receber
     */
    public function convertToReceivable()
    {
        // Verifica se o orçamento está aprovado
        if ($this->status !== 'aprovado') {
            throw new \Exception('Apenas orçamentos aprovados podem ser convertidos em contas a receber.');
        }
        
        // Verifica se já foi convertido
        if ($this->converted_to_receivable) {
            return false;
        }
        
        // Verifica se tem método de pagamento
        if (!$this->payment_method_id) {
            throw new \Exception('O orçamento precisa ter um método de pagamento definido.');
        }
        
        // Verifica se tem parcelas geradas
        if ($this->installments->isEmpty()) {
            throw new \Exception('O orçamento não possui parcelas geradas.');
        }
        
        // Categoria financeira padrão para receitas de orçamentos
        $categoriaReceita = FinancialCategory::where('natureza', 'receita')
            ->where('nome', 'Vendas')
            ->first();
            
        if (!$categoriaReceita) {
            throw new \Exception('Categoria financeira "Vendas" não encontrada.');
        }
        
        // Processa cada parcela
        foreach ($this->installments as $parcela) {
            // Pula se já foi convertida
            if ($parcela->isConverted()) continue;
            
            // Cria a conta a receber
            $contaReceber = FinancialAccount::create([
                'category_id' => $categoriaReceita->id,
                'descricao' => "Orçamento #{$this->numero} - Parcela {$parcela->numero_parcela}/{$this->payment_installments}",
                'valor' => $parcela->valor,
                'tipo' => 'receita',
                'status' => 'pendente',
                'data_vencimento' => $parcela->data_vencimento,
                'observacoes' => "Gerado automaticamente a partir do orçamento #{$this->numero} para o cliente {$this->client->nome}."
            ]);
            
            // Vincula a conta à parcela
            $parcela->update(['financial_account_id' => $contaReceber->id]);
        }
        
        // Marca o orçamento como convertido
        $this->update(['converted_to_receivable' => true]);
        
        return true;
    }
} 