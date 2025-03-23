<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrentAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'descricao',
        'bank_id',
        'agencia',
        'conta',
        'digito',
        'saldo_inicial',
        'data_saldo_inicial',
        'ativo',
        'codigo_externo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'saldo_inicial' => 'decimal:2',
        'data_saldo_inicial' => 'date'
    ];

    // Tipos de conta
    const TIPOS = [
        'corrente' => 'Conta Corrente',
        'poupanca' => 'Conta Poupança',
        'investimento' => 'Conta Investimento',
        'outros' => 'Outros'
    ];

    // Relacionamentos
    public function banco()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function paymentMethodAccounts()
    {
        return $this->hasMany(PaymentMethodAccount::class);
    }

    // Acessores
    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }
} 