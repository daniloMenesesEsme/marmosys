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
        'ativo',
        'region_id'
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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Obtém as áreas de atendimento deste vendedor
     */
    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }

    /**
     * Obtém as localidades atendidas por este vendedor
     */
    public function locations()
    {
        return $this->hasManyThrough(
            Location::class,
            ServiceArea::class,
            'seller_id',
            'id',
            'id',
            'location_id'
        );
    }

    /**
     * Obtém os tipos de estabelecimento atendidos por este vendedor
     */
    public function establishmentTypes()
    {
        return $this->hasManyThrough(
            EstablishmentType::class,
            ServiceArea::class,
            'seller_id',
            'id',
            'id',
            'establishment_type_id'
        );
    }

    /**
     * Verifica se o vendedor atende uma determinada localidade
     */
    public function servesLocation($locationId, $establishmentTypeId = null)
    {
        $query = $this->serviceAreas()
            ->where('location_id', $locationId)
            ->where('status', 'active');
            
        if ($establishmentTypeId) {
            $query->where(function($q) use ($establishmentTypeId) {
                $q->where('establishment_type_id', $establishmentTypeId)
                  ->orWhereNull('establishment_type_id');
            });
        }
        
        return $query->exists();
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
