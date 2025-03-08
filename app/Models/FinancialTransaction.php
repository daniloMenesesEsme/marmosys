<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'descricao',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'categoria_id',
        'conta_id',
        'cliente_id',
        'orcamento_id'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'conta_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'orcamento_id');
    }
} 