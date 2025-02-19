<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor_meta',
        'valor_atual',
        'data_inicial',
        'data_final',
        'status',
        'observacoes',
        'percentual'
    ];

    protected $casts = [
        'data_inicial' => 'datetime',
        'data_final' => 'datetime',
        'valor_meta' => 'decimal:2',
        'valor_atual' => 'decimal:2',
        'percentual' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'categoria_id');
    }

    public function getProgressoAttribute()
    {
        $valorRealizado = FinancialAccount::query()
            ->where('category_id', $this->categoria_id)
            ->where('status', 'pago')
            ->whereBetween('data_pagamento', [$this->data_inicial, $this->data_final])
            ->sum('valor');

        if ($this->tipo === 'economia') {
            // Para metas de economia, considera despesas
            $valorRealizado = $this->valor_meta - $valorRealizado;
        }

        return min(100, round(($valorRealizado / $this->valor_meta) * 100));
    }

    public function getValorRealizadoAttribute()
    {
        $valor = FinancialAccount::query()
            ->where('category_id', $this->categoria_id)
            ->where('status', 'pago')
            ->whereBetween('data_pagamento', [$this->data_inicial, $this->data_final])
            ->sum('valor');

        if ($this->tipo === 'economia') {
            // Para metas de economia, considera despesas
            return $this->valor_meta - $valor;
        }

        return $valor;
    }

    public function getDiasRestantesAttribute()
    {
        return max(0, Carbon::now()->diffInDays($this->data_final, false));
    }

    // Calcula o percentual de conclusão
    public function getPercentualAttribute()
    {
        if ($this->valor_meta > 0) {
            return round(($this->valor_atual / $this->valor_meta) * 100, 2);
        }
        return 0;
    }

    // Verifica se a meta está atrasada
    public function getAtrasadaAttribute()
    {
        return $this->status == 'em_andamento' && $this->data_final < now();
    }

    // Escopo para metas em andamento
    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }

    // Escopo para metas concluídas
    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }
} 