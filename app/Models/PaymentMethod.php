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
        'descricao',
        'especie_documento',
        'categoria_financeira_id',
        'agente',
        'controle_cartao',
        'movimenta_conta_corrente',
        'emite_comprovantes_vinculados',
        'ativo',
        'envia_pdv',
        // Campos PDV
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
        'sangria_automatica' => 'boolean'
    ];

    // Tipos de pagamento disponíveis
    const TIPOS = [
        'dinheiro' => 'Dinheiro',
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto',
        'outros' => 'Outros'
    ];

    // Escopo para métodos à vista
    public function scopeAvista($query)
    {
        return $query->where('pagamento_avista', true);
    }

    // Escopo para métodos ativos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Verifica se é um método que só aceita à vista
    public function isAvistaOnly()
    {
        return in_array($this->especie_documento, ['dinheiro', 'pix']);
    }

    // Retorna o nome formatado do tipo
    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    public function categoriaFinanceira()
    {
        return $this->belongsTo(FinancialCategory::class, 'categoria_financeira_id');
    }

    public function contasCorrentes()
    {
        return $this->hasMany(PaymentMethodAccount::class);
    }

    // Boot do modelo para garantir regras de negócio
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($paymentMethod) {
            // Se for dinheiro ou pix, força pagamento à vista
            if ($paymentMethod->isAvistaOnly()) {
                $paymentMethod->pagamento_avista = true;
                $paymentMethod->parcelas_padrao = 1;
                $paymentMethod->taxa_padrao = 0;
            }
        });
    }
} 