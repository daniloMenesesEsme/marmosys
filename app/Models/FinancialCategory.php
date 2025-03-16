<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'tipo',
        'codigo_contabil_externo',
        'cor',
        'icone',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Constantes para os tipos de categoria
    const TIPO_ANALITICA = 'analitica';
    const TIPO_SINTETICA = 'sintetica';

    const TIPOS = [
        self::TIPO_ANALITICA => 'Analítica',
        self::TIPO_SINTETICA => 'Sintética'
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

    // Escopo para categorias analíticas
    public function scopeAnaliticas($query)
    {
        return $query->where('tipo', self::TIPO_ANALITICA);
    }

    // Escopo para categorias sintéticas
    public function scopeSinteticas($query)
    {
        return $query->where('tipo', self::TIPO_SINTETICA);
    }

    // Relacionamento com formas de pagamento
    public function formasPagamento()
    {
        return $this->hasMany(PaymentMethod::class, 'categoria_financeira_id');
    }

    // Verifica se é uma categoria analítica
    public function isAnalitica()
    {
        return $this->tipo === self::TIPO_ANALITICA;
    }

    // Verifica se é uma categoria sintética
    public function isSintetica()
    {
        return $this->tipo === self::TIPO_SINTETICA;
    }

    // Retorna o nome formatado do tipo
    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }
} 