<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'state',
        'status'
    ];

    // Relacionamento com vendedores
    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }
} 