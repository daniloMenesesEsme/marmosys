<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_room_id',
        'material_id',
        'quantidade',
        'unidade',
        'descricao',
        'largura',
        'altura',
        'valor_unitario',
        'valor_total'
    ];

    protected $casts = [
        'quantidade' => 'decimal:3',
        'largura' => 'decimal:3',
        'altura' => 'decimal:3',
        'valor_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2'
    ];

    public function room()
    {
        return $this->belongsTo(BudgetRoom::class, 'budget_room_id');
    }

    public function material()
    {
        return $this->belongsTo(BudgetMaterial::class, 'material_id');
    }
} 