<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'apelido',
        'cpf_cnpj',
        'rg',
        'crt',
        'cnae',
        'data_nascimento',
        'telefone',
        'is_cliente',
        'is_fornecedor',
        'is_vendedor',
        'is_transportador',
        'is_condutor',
        'is_contador',
        'is_intermediador',
        'tipo_pessoa',
        'genero',
        'is_produtor_rural',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'pais',
        'uf',
        'municipio',
        'email',
        'website',
        'observacoes',
        'foto_path',
        'ativo'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'is_cliente' => 'boolean',
        'is_fornecedor' => 'boolean',
        'is_vendedor' => 'boolean',
        'is_transportador' => 'boolean',
        'is_condutor' => 'boolean',
        'is_contador' => 'boolean',
        'is_intermediador' => 'boolean',
        'is_produtor_rural' => 'boolean',
        'ativo' => 'boolean'
    ];

    // Accessor para retornar a URL da foto
    public function getFotoUrlAttribute()
    {
        return $this->foto_path
            ? asset('storage/' . $this->foto_path)
            : asset('images/default-avatar.png');
    }
} 