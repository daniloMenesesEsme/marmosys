<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'endereco',
        'cargo',
        'data_admissao',
        'ativo'
    ];

    protected $casts = [
        'data_admissao' => 'date',
        'ativo' => 'boolean'
    ];
} 