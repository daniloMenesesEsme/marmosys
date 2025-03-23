<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'agencia',
        'ativo',
        'banco',
        'conta',
        'cpf_cnpj',
        'financial_agent_id',
        'nome',
        'saldo_inicial',
        'tipo_conta',
        'titular',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'id' => 'int',
        'saldo_inicial' => 'decimal:2',
    ];

    public function financialAgent()
    {
        return $this->belongsTo(FinancialAgent::class);
    }

    // Método para sincronizar dados com o agente financeiro
    public function syncWithAgent()
    {
        if ($this->financial_agent_id && $this->financialAgent->isBanco()) {
            $this->banco = $this->financialAgent->codigo_banco;
            $this->agencia = $this->financialAgent->agencia;
            $this->conta = $this->financialAgent->conta;
            $this->digito = $this->financialAgent->digito;
            $this->save();
        }
    }
} 