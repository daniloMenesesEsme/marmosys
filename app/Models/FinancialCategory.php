<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function accounts()
    {
        return $this->hasMany(FinancialAccount::class, 'category_id');
    }

    public function getTipoTextAttribute()
    {
        return [
            'receita' => 'Receita',
            'despesa' => 'Despesa'
        ][$this->tipo] ?? $this->tipo;
    }

    public function getTipoClassAttribute()
    {
        return [
            'receita' => 'green white-text',
            'despesa' => 'red white-text'
        ][$this->tipo] ?? '';
    }

    // Escopo para categorias ativas
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
} 