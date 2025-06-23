<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id', 
        'establishment_type_id', 
        'seller_id', 
        'goal_amount', 
        'notes', 
        'status'
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2'
    ];

    /**
     * Obtém a localidade desta área de atendimento
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Obtém o tipo de estabelecimento desta área de atendimento
     */
    public function establishmentType()
    {
        return $this->belongsTo(EstablishmentType::class);
    }

    /**
     * Obtém o vendedor responsável por esta área de atendimento
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Obtém os clientes associados a esta área de atendimento
     */
    public function clients()
    {
        return Client::where('location_id', $this->location_id)
                ->where(function($query) {
                    // Se o tipo de estabelecimento for especificado, filtra por ele
                    if ($this->establishment_type_id) {
                        $query->where('establishment_type_id', $this->establishment_type_id);
                    }
                });
    }

    /**
     * Calcula o desempenho nesta área de atendimento
     */
    public function performanceStats($startDate = null, $endDate = null)
    {
        $query = Budget::query()
            ->where('seller_id', $this->seller_id)
            ->whereHas('client', function($query) {
                $query->where('location_id', $this->location_id);
                if ($this->establishment_type_id) {
                    $query->where('establishment_type_id', $this->establishment_type_id);
                }
            });
        
        if ($startDate) {
            $query->whereDate('data', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('data', '<=', $endDate);
        }
        
        $total = $query->sum('valor_final');
        $count = $query->count();
        
        return [
            'total_sales' => $total,
            'sale_count' => $count,
            'goal_amount' => $this->goal_amount,
            'goal_percentage' => $this->goal_amount > 0 ? ($total / $this->goal_amount) * 100 : 0
        ];
    }

    /**
     * Escopo para filtrar apenas áreas de atendimento ativas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
