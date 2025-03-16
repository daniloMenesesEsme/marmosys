<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method_id',
        'loja_id',
        'conta_corrente_id'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function loja()
    {
        return $this->belongsTo(Store::class, 'loja_id');
    }

    public function contaCorrente()
    {
        return $this->belongsTo(BankAccount::class, 'conta_corrente_id');
    }
}
