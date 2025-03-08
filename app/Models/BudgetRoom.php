<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetRoom extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'budget_id',
        'nome',
        'valor_total'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class, 'budget_room_id');
    }
} 