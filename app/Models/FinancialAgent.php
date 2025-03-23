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

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
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

    // Acessores
    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    public function getContaCompletoAttribute()
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

    public function getDadosBancariosAttribute()
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

    // Métodos
    public function isBanco()
    {
        return $this->tipo === 'banco';
    }

    public function isFinanceira()
    {
        return $this->tipo === 'financeira';
    }

    public function podeSerExcluido()
    {
        return $this->paymentMethods()->count() === 0 &&
               $this->transactions()->count() === 0 &&
               $this->bankAccounts()->count() === 0;
    }

    public function verificarDependencias()
    {
        $dependencias = [];

        if ($this->paymentMethods()->count() > 0) {
            $dependencias[] = 'Formas de Pagamento';
        }

        if ($this->transactions()->count() > 0) {
            $dependencias[] = 'Transações Financeiras';
        }

        if ($this->bankAccounts()->count() > 0) {
            $dependencias[] = 'Contas Bancárias';
        }

        return $dependencias;
    }

    // Boot do modelo
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($agent) {
            // Se não for banco, limpa campos bancários
            if (!$agent->isBanco()) {
                $agent->codigo_banco = null;
                $agent->agencia = null;
                $agent->conta = null;
                $agent->digito = null;
            }

            // Se não for financeira, limpa CNPJ
            if (!$agent->isFinanceira()) {
                $agent->cnpj = null;
            }
        });
    }
} 