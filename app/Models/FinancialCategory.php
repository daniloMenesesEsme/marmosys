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
        'natureza',
        'cor',
        'icone',
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

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'category_id');
    }

    public function agents()
    {
        return $this->hasMany(FinancialAgent::class, 'financial_category_id');
    }

    public function getTipoTextAttribute()
    {
        return [
            'ANALITICA' => 'Analítica',
            'SINTETICA' => 'Sintética'
        ][$this->tipo] ?? $this->tipo;
    }

    public function getNaturezaTextAttribute()
    {
        return [
            'receita' => 'Receita',
            'despesa' => 'Despesa'
        ][$this->natureza] ?? $this->natureza;
    }

    public function getTipoClassAttribute()
    {
        return [
            'ANALITICA' => 'blue white-text',
            'SINTETICA' => 'purple white-text'
        ][$this->tipo] ?? '';
    }

    public function getNaturezaClassAttribute()
    {
        return [
            'receita' => 'green white-text',
            'despesa' => 'red white-text'
        ][$this->natureza] ?? '';
    }

    // Escopo para categorias ativas
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
} 