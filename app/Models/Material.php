<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'codigo',
        'nome',
        'descricao',
        'unidade_medida',
        'estoque_minimo',
        'estoque_atual',
        'preco_custo',
        'preco_venda',
        'ativo'
    ];

    protected $casts = [
        'estoque_minimo' => 'decimal:2',
        'estoque_atual' => 'decimal:2',
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'ativo' => 'boolean'
    ];

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