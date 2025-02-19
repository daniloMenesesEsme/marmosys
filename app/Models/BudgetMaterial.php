<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetMaterial extends Model
{
    protected $fillable = ['nome', 'preco_padrao', 'unidade_medida', 'ativo'];
} 