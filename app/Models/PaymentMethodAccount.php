<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethodAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_method_id',
        'loja_id',
        'conta_corrente_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'principal' => 'boolean'
    ];

    // Relacionamentos
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'loja_id');
    }

    public function currentAccount()
    {
        return $this->belongsTo(CurrentAccount::class, 'conta_corrente_id');
    }
}
