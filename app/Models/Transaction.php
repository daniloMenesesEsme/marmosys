<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'financial_accounts';

    protected $fillable = [
        'data_vencimento',
        'data_pagamento',
        'valor',
        'tipo',
        'status',
        'descricao',
        'observacoes'
    ];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'data_pagamento' => 'datetime'
    ];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }
} 