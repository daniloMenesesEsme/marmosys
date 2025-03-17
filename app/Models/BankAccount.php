<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'financial_agent_id'
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