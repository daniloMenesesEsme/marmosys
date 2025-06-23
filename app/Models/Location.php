<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'type', 
        'parent_id', 
        'code', 
        'latitude', 
        'longitude', 
        'description', 
        'status'
    ];

    /**
     * Obtém a localidade pai
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Obtém as localidades filhas
     */
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Obtém todas as áreas de atendimento desta localidade
     */
    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }

    /**
     * Obtém todos os clientes associados a esta localidade
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Obtém todos os vendedores que atendem esta localidade
     */
    public function sellers()
    {
        return $this->hasManyThrough(
            Seller::class,
            ServiceArea::class,
            'location_id', // Chave local na tabela intermediária
            'id', // Chave na tabela de destino
            'id', // Chave local
            'seller_id' // Chave na tabela intermediária que referencia a tabela de destino
        );
    }

    /**
     * Escopo para filtrar apenas localidades ativas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Escopo para filtrar por tipo de localidade
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Obtém o caminho completo da localidade (incluindo hierarquia)
     */
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }
}
