<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProductType;
use App\Interfaces\Itemizable;

class Product extends Model implements Itemizable
{
    protected $fillable = [
        'tipo',
        'codigo',
        'nome',
        'descricao',
        'preco_custo',
        'preco_venda',
        'estoque',
        'estoque_minimo',
        'unidade_medida',
        'categoria',
        'fornecedor',
        'ativo'
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'estoque_atual' => 'decimal:2',
        'estoque_minimo' => 'decimal:2',
        'ativo' => 'boolean',
        'tipo' => ProductType::class
    ];

    public function getMargemLucroAttribute()
    {
        if ($this->preco_custo > 0) {
            return (($this->preco_venda - $this->preco_custo) / $this->preco_custo) * 100;
        }
        return 0;
    }

    public function getStatusEstoqueAttribute()
    {
        if ($this->estoque <= 0) {
            return ['text' => 'Sem Estoque', 'class' => 'red white-text'];
        }
        if ($this->estoque <= $this->estoque_minimo) {
            return ['text' => 'Estoque Baixo', 'class' => 'orange white-text'];
        }
        return ['text' => 'Estoque Normal', 'class' => 'green white-text'];
    }

    public function movimentos()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function ajustarEstoque($quantidade, $tipo, $observacao)
    {
        $saldoAnterior = $this->estoque;
        
        if ($tipo === 'entrada') {
            $this->estoque += $quantidade;
        } else {
            $this->estoque -= $quantidade;
        }

        $this->save();

        // Registra o movimento
        return $this->movimentos()->create([
            'tipo' => $tipo,
            'quantidade' => $quantidade,
            'saldo_anterior' => $saldoAnterior,
            'saldo_atual' => $this->estoque,
            'observacao' => $observacao,
            'user_id' => auth()->id()
        ]);
    }

    public function getName(): string
    {
        return $this->nome;
    }

    public function getPrice(): float
    {
        return $this->preco_venda;
    }

    public function getType(): string
    {
        return $this->tipo->value;
    }

    public function getMeasureUnit(): string
    {
        return $this->unidade_medida;
    }

    /**
     * Gera o próximo código disponível baseado no tipo
     */
    public static function generateNextCode(string $tipo): string
    {
        // Converte o tipo string para enum
        $typeEnum = ProductType::from($tipo);
        $prefixo = $typeEnum->prefix();

        // Busca o último código com este prefixo
        $lastProduct = self::where('codigo', 'like', $prefixo.'%')
            ->orderBy('codigo', 'desc')
            ->first();

        if (!$lastProduct) {
            return $prefixo.'0001';
        }

        // Extrai o número do último código
        preg_match('/\d+$/', $lastProduct->codigo, $matches);
        $lastNumber = (int) ($matches[0] ?? 0);
        $nextNumber = $lastNumber + 1;

        // Formata com zeros à esquerda (4 dígitos)
        return $prefixo.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
} 