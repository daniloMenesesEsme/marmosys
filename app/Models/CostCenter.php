<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function financial_accounts()
    {
        return $this->hasMany(FinancialAccount::class);
    }

    // Métodos de escopo para consultas comuns
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Método para calcular o total de despesas
    public function getTotalDespesasAttribute()
    {
        return $this->financial_accounts()
            ->where('tipo', 'despesa')
            ->where('status', 'pago')
            ->sum('valor');
    }

    // Método para calcular o total de receitas
    public function getTotalReceitasAttribute()
    {
        return $this->financial_accounts()
            ->where('tipo', 'receita')
            ->where('status', 'pago')
            ->sum('valor');
    }
} 