<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'tipo',
        'agente',
        'categoria_financeira_id',
        'financial_agent_id',
        'controle_cartao',
        'movimenta_conta_corrente',
        'emite_comprovantes_vinculados',
        'ativo',
        'parcelas_padrao',
        'taxa_padrao',
        'envia_pdv',
        'especie_pdv',
        'pagamento',
        'sangria_automatica',
        'tipo_cliente',
        'pin_pad',
        'prazo',
        'identificador'
    ];

    protected $casts = [
        'controle_cartao' => 'boolean',
        'movimenta_conta_corrente' => 'boolean',
        'emite_comprovantes_vinculados' => 'boolean',
        'ativo' => 'boolean',
        'envia_pdv' => 'boolean',
        'pagamento' => 'boolean',
        'sangria_automatica' => 'boolean',
        'pin_pad' => 'boolean',
        'parcelas_padrao' => 'integer',
        'taxa_padrao' => 'decimal:2',
        'prazo' => 'integer'
    ];

    // Tipos de pagamento disponíveis
    const TIPOS = [
        'dinheiro' => 'Dinheiro',
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto',
        'outros' => 'Outros'
    ];

    // Bandeiras de cartão para TEF
    const BANDEIRAS_TEF = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'elo' => 'Elo',
        'amex' => 'American Express',
        'hipercard' => 'Hipercard',
        'diners' => 'Diners Club',
        'discover' => 'Discover',
        'jcb' => 'JCB',
        'aura' => 'Aura',
        'outros' => 'Outros'
    ];

    // Relacionamentos
    public function categoriaFinanceira()
    {
        return $this->belongsTo(FinancialCategory::class, 'categoria_financeira_id');
    }

    public function financialAgent()
    {
        return $this->belongsTo(FinancialAgent::class);
    }

    public function contasCorrentes()
    {
        return $this->hasMany(PaymentMethodAccount::class);
    }

    public function planosPagamento()
    {
        return $this->hasMany(PaymentPlan::class);
    }

    // Escopos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeAvista($query)
    {
        return $query->whereIn('tipo', ['dinheiro', 'pix']);
    }

    public function scopeParcelado($query)
    {
        return $query->whereIn('tipo', ['cartao', 'boleto']);
    }

    // Acessores
    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    public function getIsAvistaAttribute()
    {
        return in_array($this->tipo, ['dinheiro', 'pix']);
    }

    // Métodos
    public function isAvistaOnly()
    {
        return in_array($this->tipo, ['dinheiro', 'pix']);
    }

    public function requiresFinancialAgent()
    {
        return in_array($this->tipo, ['cartao', 'boleto']);
    }

    public function requiresAccount()
    {
        return $this->movimenta_conta_corrente;
    }

    // Boot do modelo
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($paymentMethod) {
            // Se for dinheiro ou pix, força pagamento à vista
            if ($paymentMethod->isAvistaOnly()) {
                $paymentMethod->parcelas_padrao = 1;
                $paymentMethod->taxa_padrao = 0;
            }

            // Se não requer agente financeiro, limpa o campo
            if (!$paymentMethod->requiresFinancialAgent()) {
                $paymentMethod->financial_agent_id = null;
            }
        });
    }
} 