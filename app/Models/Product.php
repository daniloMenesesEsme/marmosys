<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'preco_custo',
        'preco_venda',
        'estoque_atual',
        'estoque_minimo',
        'unidade_medida',
        'categoria',
        'fornecedor',
        'ativo'
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'estoque_atual' => 'decimal:2',
        'estoque_minimo' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    public function getMargemLucroAttribute()
    {
        if ($this->preco_custo > 0) {
            return (($this->preco_venda - $this->preco_custo) / $this->preco_custo) * 100;
        }
        return 0;
    }

    public function getStatusEstoqueAttribute()
    {
        if ($this->estoque_atual <= 0) {
            return ['text' => 'Sem Estoque', 'class' => 'red white-text'];
        }
        if ($this->estoque_atual <= $this->estoque_minimo) {
            return ['text' => 'Estoque Baixo', 'class' => 'orange white-text'];
        }
        return ['text' => 'Estoque Normal', 'class' => 'green white-text'];
    }

    public function movimentos()
    {
        return $this->hasMany(StockMovement::class);
    }
} 