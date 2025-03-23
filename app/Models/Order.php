<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'budget_id',
        'numero',
        'status',
        'data_pedido',
        'data_entrega',
        'observacoes'
    ];

    protected $casts = [
        'data_pedido' => 'date',
        'data_entrega' => 'date'
    ];

    /**
     * Relacionamento com o orçamento que originou o pedido
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Retorna uma descrição textual do status do pedido
     */
    public function getStatusTextAttribute()
    {
        return [
            'aguardando_producao' => 'Aguardando Produção',
            'em_producao' => 'Em Produção',
            'pronto_para_entrega' => 'Pronto para Entrega',
            'entregue' => 'Entregue',
            'cancelado' => 'Cancelado'
        ][$this->status] ?? $this->status;
    }

    /**
     * Retorna a classe CSS para o status atual
     */
    public function getStatusClassAttribute()
    {
        return [
            'aguardando_producao' => 'orange',
            'em_producao' => 'blue',
            'pronto_para_entrega' => 'teal',
            'entregue' => 'green',
            'cancelado' => 'red'
        ][$this->status] ?? 'grey';
    }
}
