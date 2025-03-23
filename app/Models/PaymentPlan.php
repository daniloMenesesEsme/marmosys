<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'parcelas',
        'intervalo_dias',
        'carencia_dias',
        'taxa',
        'multa_atraso',
        'juros_atraso',
        'permite_entrada',
        'percentual_minimo_entrada',
        'valor_minimo_parcela',
        'limite_credito',
        'payment_method_id',
        'codigo_xml',
        'disponivel_pdv',
        'ordem_exibicao',
        'ativo',
        'requer_aprovacao'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'permite_entrada' => 'boolean',
        'disponivel_pdv' => 'boolean',
        'requer_aprovacao' => 'boolean',
        'taxa' => 'decimal:2',
        'multa_atraso' => 'decimal:2',
        'juros_atraso' => 'decimal:2',
        'percentual_minimo_entrada' => 'decimal:2',
        'valor_minimo_parcela' => 'decimal:2',
        'limite_credito' => 'decimal:2'
    ];

    // Relacionamentos
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function restrictions()
    {
        return $this->hasMany(PaymentPlanRestriction::class);
    }

    // Escopos
    public function scopeVendas($query)
    {
        return $query->where('tipo', 'venda');
    }

    public function scopeCompras($query)
    {
        return $query->where('tipo', 'compra');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeDisponiveisPDV($query)
    {
        return $query->where('disponivel_pdv', true)
                    ->where('tipo', 'venda')
                    ->orderBy('ordem_exibicao');
    }

    // Métodos
    public function calcularParcelas($valor)
    {
        $parcelas = [];
        $valorParcela = $valor / $this->parcelas;

        if ($valorParcela < $this->valor_minimo_parcela) {
            return false;
        }

        for ($i = 1; $i <= $this->parcelas; $i++) {
            $parcelas[] = [
                'numero' => $i,
                'valor' => $valorParcela,
                'vencimento' => now()->addDays($this->carencia_dias + ($this->intervalo_dias * ($i - 1))),
                'taxa' => $this->taxa
            ];
        }

        return $parcelas;
    }

    public function verificarRestricao($tipo, $id)
    {
        return $this->restrictions()
                    ->where('tipo_restricao', $tipo)
                    ->where('restricao_id', $id)
                    ->exists();
    }
} 