<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'data',
        'previsao_entrega',
        'client_id',
        'status',
        'valor_total',
        'desconto',
        'valor_final',
        'data_validade',
        'user_id',
        'observacoes'
    ];

    protected $casts = [
        'data' => 'date',
        'previsao_entrega' => 'date',
        'data_validade' => 'date',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_final' => 'decimal:2'
    ];

    protected $dates = [
        'data',
        'data_validade'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function rooms()
    {
        return $this->hasMany(BudgetRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusTextAttribute()
    {
        return [
            'rascunho' => 'Rascunho',
            'aguardando_aprovacao' => 'Aguardando Aprovação',
            'aprovado' => 'Aprovado',
            'reprovado' => 'Reprovado',
            'convertido' => 'Convertido em Pedido'
        ][$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute()
    {
        return [
            'rascunho' => 'grey',
            'aguardando_aprovacao' => 'orange',
            'aprovado' => 'green',
            'reprovado' => 'red',
            'convertido' => 'blue'
        ][$this->status] ?? 'grey';
    }

    public function recalcularTotal()
    {
        $this->valor_total = $this->rooms->sum('valor_total');
        $this->valor_final = $this->valor_total - ($this->desconto ?? 0);
        $this->save();
    }

    public function converterEmPedido()
    {
        if ($this->status !== 'aprovado') {
            throw new \Exception('Apenas orçamentos aprovados podem ser convertidos em pedidos.');
        }

        $order = Order::create([
            'budget_id' => $this->id,
            'numero' => 'PED-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT),
            'status' => 'aguardando_producao'
        ]);

        $this->update(['status' => 'convertido']);

        return $order;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            $budget->numero = 'ORC-' . date('Y') . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 5, '0', STR_PAD_LEFT);
        });
    }
} 