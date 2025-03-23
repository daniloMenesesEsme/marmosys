<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'nome',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Relacionamentos
    public function paymentMethodAccounts()
    {
        return $this->hasMany(PaymentMethodAccount::class);
    }

    public function currentAccounts()
    {
        return $this->hasMany(CurrentAccount::class);
    }
} 