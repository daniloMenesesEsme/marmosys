<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'data',
        'previsao_entrega',
        'client_id',
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
        'tipo_cliente',
        'fator_multiplicador',
        'rt_percentual',
        'rt_material',
        'rt_colocacao',
        'rt_produto',
        'rt_frete',
        'condicoes_pagamento',
        'prazo_entrega',
        'observacoes_padrao'
    ];

    protected $casts = [
        'data' => 'date',
        'previsao_entrega' => 'date',
        'data_validade' => 'date',
        'approved_at' => 'datetime',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'fator_multiplicador' => 'decimal:2',
        'rt_percentual' => 'decimal:2',
        'rt_material' => 'boolean',
        'rt_colocacao' => 'boolean',
        'rt_produto' => 'boolean',
        'rt_frete' => 'boolean'
    ];

    protected $dates = [
        'data',
        'data_validade'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function rooms()
    {
        return $this->hasMany(BudgetRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
            $budget->numero = 'ORC-' . date('Y') . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 5, '0', STR_PAD_LEFT);
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
} 