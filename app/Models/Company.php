<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'telefone',
        'celular',
        'email',
        'website',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'observacoes',
        'logo'
    ];

    // Formatar CNPJ para exibição
    public function getFormattedCnpjAttribute()
    {
        $cnpj = $this->cnpj;
        if (strlen($cnpj) == 14) {
            return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . 
                   substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
        }
        return $cnpj;
    }

    // Formatar telefone para exibição
    public function getFormattedTelefoneAttribute()
    {
        $telefone = $this->telefone;
        if (strlen($telefone) == 10) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
        } elseif (strlen($telefone) == 11) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
        }
        return $telefone;
    }

    // Endereço completo
    public function getEnderecoCompletoAttribute()
    {
        $endereco = $this->logradouro;
        if ($this->numero) $endereco .= ', ' . $this->numero;
        if ($this->complemento) $endereco .= ' - ' . $this->complemento;
        if ($this->bairro) $endereco .= ', ' . $this->bairro;
        if ($this->cidade) $endereco .= ', ' . $this->cidade;
        if ($this->estado) $endereco .= ' - ' . $this->estado;
        if ($this->cep) $endereco .= ', CEP: ' . $this->cep;
        
        return $endereco;
    }
}
