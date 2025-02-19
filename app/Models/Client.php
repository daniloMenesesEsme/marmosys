<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'endereco',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
} 