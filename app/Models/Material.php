<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'preco_padrao',
        'unidade_medida',
        'ativo',
        'estoque_minimo',
        'estoque_atual',
        'preco_custo',
        'preco_venda',
        'category_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'deleted_at' => 'datetime',
        'estoque_atual' => 'decimal:2',
        'estoque_minimo' => 'decimal:2',
        'id' => 'int',
        'preco_custo' => 'decimal:2',
        'preco_padrao' => 'decimal:2',
        'preco_venda' => 'decimal:2'
    ];

    public function budgetItems()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($material) {
            if (empty($material->codigo)) {
                $prefix = substr(strtoupper($material->category->tipo), 0, 3);
                $nextId = static::where('category_id', $material->category_id)->count() + 1;
                $material->codigo = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }
} 