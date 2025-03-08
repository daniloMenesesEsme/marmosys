<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $table = 'financial_transactions';

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

    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function conta()
    {
        return $this->belongsTo(Account::class, 'conta_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function orcamento()
    {
        return $this->belongsTo(Budget::class, 'orcamento_id');
    }
} 