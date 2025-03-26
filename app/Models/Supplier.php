<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'telefone',
        'email',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Formatar CNPJ para exibição
    public function getFormattedCnpjAttribute()
    {
        if (!$this->cnpj) return null;

        $cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
        
        return substr($cnpj, 0, 2) . '.' . 
               substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . 
               substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
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
        if ($this->cep) $endereco[] = 'CEP: ' . $this->formatted_cep;

        return implode(' - ', array_filter($endereco));
    }

    // Retorna o status formatado
    public function getStatusAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    // Scope para filtrar apenas fornecedores ativos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Scope para filtrar apenas fornecedores inativos
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }
} 