<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'material_id',
        'largura',
        'altura',
        'profundidade',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'observacoes'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
} 