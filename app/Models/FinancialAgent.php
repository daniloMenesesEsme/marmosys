<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAgent extends Model
{
    use HasFactory, SoftDeletes;

    const TIPOS = [
        'banco' => 'Banco',
        'financeira' => 'Financeira',
        'outros' => 'Outros'
    ];

    protected $fillable = [
        'codigo',
        'nome',
        'tipo',
        'status',
        'financial_category_id',
        'cost_center_id',
        'codigo_banco',
        'agencia',
        'conta',
        'digito',
        'cnpj',
        'observacoes'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relacionamentos
    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    // Métodos de negócio
    public function isBanco(): bool
    {
        return $this->tipo === 'banco';
    }

    public function isFinanceira(): bool
    {
        return $this->tipo === 'financeira';
    }

    public function getContaCompletoAttribute(): string
    {
        if (!$this->isBanco()) {
            return '';
        }

        return sprintf(
            '%s-%s',
            $this->conta,
            $this->digito
        );
    }

    public function getDadosBancariosAttribute(): string
    {
        if (!$this->isBanco()) {
            return '';
        }

        return sprintf(
            'Banco: %s | Agência: %s | Conta: %s-%s',
            $this->codigo_banco,
            $this->agencia,
            $this->conta,
            $this->digito
        );
    }

    // Escopos
    public function scopeAtivos($query)
    {
        return $query->where('status', true);
    }

    public function scopeBancos($query)
    {
        return $query->where('tipo', 'banco');
    }

    public function scopeFinanceiras($query)
    {
        return $query->where('tipo', 'financeira');
    }

    // Validações de negócio
    public function podeSerExcluido(): bool
    {
        return $this->paymentMethods()->count() === 0;
    }

    public function verificarDependencias(): array
    {
        $dependencias = [];

        if ($this->paymentMethods()->count() > 0) {
            $dependencias[] = 'Formas de Pagamento';
        }

        return $dependencias;
    }
} 