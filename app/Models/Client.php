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
        'rg_ie',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'ativo',
        'latitude',
        'longitude',
        'empresa',
        'contato',
        'location_id',
        'establishment_type_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
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

    /**
     * Obtém a localidade deste cliente
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Obtém o tipo de estabelecimento deste cliente
     */
    public function establishmentType()
    {
        return $this->belongsTo(EstablishmentType::class);
    }

    /**
     * Verifica se o cliente pertence a área de atendimento de um vendedor
     */
    public function belongsToSellerArea($sellerId)
    {
        if (!$this->location_id) {
            return false;
        }
        
        return ServiceArea::where('location_id', $this->location_id)
            ->where('seller_id', $sellerId)
            ->where(function($query) {
                if ($this->establishment_type_id) {
                    $query->where('establishment_type_id', $this->establishment_type_id)
                        ->orWhereNull('establishment_type_id');
                }
            })
            ->exists();
    }

    /**
     * Obtém o vendedor responsável pela área deste cliente
     */
    public function getResponsibleSellerAttribute()
    {
        if (!$this->location_id) {
            return null;
        }
        
        $serviceArea = ServiceArea::where('location_id', $this->location_id)
            ->where(function($query) {
                if ($this->establishment_type_id) {
                    $query->where('establishment_type_id', $this->establishment_type_id)
                        ->orWhereNull('establishment_type_id');
                }
            })
            ->where('status', 'active')
            ->first();
            
        return $serviceArea ? $serviceArea->seller : null;
    }
} 