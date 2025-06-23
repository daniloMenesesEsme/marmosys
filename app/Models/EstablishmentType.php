<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'potential_level', 
        'status'
    ];

    /**
     * Obtém todas as áreas de atendimento para este tipo de estabelecimento
     */
    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }

    /**
     * Obtém todos os clientes deste tipo de estabelecimento
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Obtém todos os vendedores que atendem este tipo de estabelecimento
     */
    public function sellers()
    {
        return $this->hasManyThrough(
            Seller::class,
            ServiceArea::class,
            'establishment_type_id',
            'id',
            'id',
            'seller_id'
        );
    }

    /**
     * Escopo para filtrar apenas tipos de estabelecimento ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Escopo para filtrar por nível de potencial mínimo
     */
    public function scopeMinPotential($query, $level)
    {
        return $query->where('potential_level', '>=', $level);
    }
}
