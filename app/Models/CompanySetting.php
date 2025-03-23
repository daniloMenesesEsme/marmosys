<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'nome_empresa',
        'cnpj',
        'endereco',
        'telefone',
        'email',
        'logo',
        'site',
        'observacoes_orcamento'
    ];
} 