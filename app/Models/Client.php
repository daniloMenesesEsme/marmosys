<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'rg',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Formatar CPF/CNPJ para exibição
    public function getFormattedCpfCnpjAttribute()
    {
        if (!$this->cpf_cnpj) return null;

        $valor = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);
        
        if (strlen($valor) === 11) {
            return substr($valor, 0, 3) . '.' . 
                   substr($valor, 3, 3) . '.' . 
                   substr($valor, 6, 3) . '-' . 
                   substr($valor, 9, 2);
        } 
        
        if (strlen($valor) === 14) {
            return substr($valor, 0, 2) . '.' . 
                   substr($valor, 2, 3) . '.' . 
                   substr($valor, 5, 3) . '/' . 
                   substr($valor, 8, 4) . '-' . 
                   substr($valor, 12, 2);
        }

        return $this->cpf_cnpj;
    }

    // Formatar telefone para exibição
    public function getFormattedTelefoneAttribute()
    {
        if (!$this->telefone) return null;

        $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
        
        if (strlen($telefone) === 11) {
            return '(' . substr($telefone, 0, 2) . ') ' . 
                   substr($telefone, 2, 5) . '-' . 
                   substr($telefone, 7, 4);
        }
        
        if (strlen($telefone) === 10) {
            return '(' . substr($telefone, 0, 2) . ') ' . 
                   substr($telefone, 2, 4) . '-' . 
                   substr($telefone, 6, 4);
        }

        return $this->telefone;
    }

    // Formatar CEP para exibição
    public function getFormattedCepAttribute()
    {
        if (!$this->cep) return null;

        $cep = preg_replace('/[^0-9]/', '', $this->cep);
        
        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
        }

        return $this->cep;
    }

    // Retorna o endereço completo formatado
    public function getEnderecoCompletoAttribute()
    {
        $endereco = [];

        if ($this->endereco) $endereco[] = $this->endereco;
        if ($this->numero) $endereco[] = $this->numero;
        if ($this->complemento) $endereco[] = $this->complemento;
        if ($this->bairro) $endereco[] = $this->bairro;
        if ($this->cidade && $this->estado) $endereco[] = $this->cidade . '/' . $this->estado;
        else {
            if ($this->cidade) $endereco[] = $this->cidade;
            if ($this->estado) $endereco[] = $this->estado;
        }
        if ($this->cep) $endereco[] = 'CEP: ' . $this->formatted_cep;

        return implode(' - ', array_filter($endereco));
    }

    // Retorna o status formatado
    public function getStatusAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    // Scope para filtrar apenas clientes ativos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Scope para filtrar apenas clientes inativos
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
} 