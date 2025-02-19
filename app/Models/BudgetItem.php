<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'descricao',
        'quantidade',
        'unidade',
        'valor_unitario'
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'valor_unitario' => 'decimal:2'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
} 