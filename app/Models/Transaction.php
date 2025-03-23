<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'financial_accounts';

    protected $fillable = [
        'category_id',
        'cost_center_id',
        'data_vencimento',
        'data_pagamento',
        'valor',
        'tipo',
        'status',
        'descricao',
        'observacoes'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'valor' => 'decimal:2'
    ];

    public function categoria()
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function centroCusto()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }
} 