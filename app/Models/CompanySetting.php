<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome_empresa',
        'cnpj',
        'telefone',
        'email',
        'endereco',
        'logo_path'
    ];
} 