<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    protected $fillable = [
        'category_id',
        'cost_center_id',
        'descricao',
        'valor',
        'tipo',
        'status',
        'data_vencimento',
        'data_pagamento',
        'forma_pagamento',
        'observacoes',
        'documento',
        'budget_id'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'valor' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function getStatusTextAttribute()
    {
        return [
            'pendente' => 'Pendente',
            'pago' => 'Pago',
            'cancelado' => 'Cancelado'
        ][$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute()
    {
        return [
            'pendente' => 'orange white-text',
            'pago' => 'green white-text',
            'cancelado' => 'grey white-text'
        ][$this->status] ?? '';
    }

    public function scopeReceitas($query)
    {
        return $query->whereHas('category', function($q) {
            $q->where('tipo', 'receita');
        });
    }

    public function scopeDespesas($query)
    {
        return $query->whereHas('category', function($q) {
            $q->where('tipo', 'despesa');
        });
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopePagas($query)
    {
        return $query->where('status', 'pago');
    }
} 