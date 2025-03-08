<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'tipo',
        'quantidade',
        'saldo_anterior',
        'saldo_atual',
        'observacao',
        'user_id'
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_atual' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 