<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'preco_venda',
        'preco_custo',
        'estoque_minimo',
        'estoque_atual',
        'unidade_medida',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function items()
    {
        return $this->hasMany(BudgetItem::class, 'material_id');
    }
} 