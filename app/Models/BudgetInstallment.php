<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'budget_id',
        'numero_parcela',
        'valor',
        'data_vencimento',
        'financial_account_id'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'valor' => 'decimal:2',
    ];

    /**
     * Relacionamento com o orçamento
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Relacionamento com a conta financeira (conta a receber)
     */
    public function financialAccount()
    {
        return $this->belongsTo(FinancialAccount::class);
    }
    
    /**
     * Verifica se a parcela já foi convertida em conta a receber
     */
    public function isConverted()
    {
        return !is_null($this->financial_account_id);
    }
}
