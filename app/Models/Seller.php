<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Seller extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'telefone',
        'celular',
        'email',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'percentual_comissao',
        'meta_mensal',
        'data_admissao',
        'data_demissao',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'percentual_comissao' => 'decimal:2',
        'meta_mensal' => 'decimal:2',
        'data_admissao' => 'date',
        'data_demissao' => 'date',
        'ativo' => 'boolean'
    ];

    protected static function booted()
    {
        static::creating(function ($seller) {
            $seller->created_by = Auth::id();
        });

        static::updating(function ($seller) {
            $seller->updated_by = Auth::id();
        });
    }

    // Relacionamentos
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    // Escopos
    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%")
                  ->orWhere('celular', 'like', "%{$search}%");
        });
    }
}
