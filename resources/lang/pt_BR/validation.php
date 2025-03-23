<?php

return [
    // ... outras validações ...

    'custom' => [
        'estoque' => [
            'integer' => 'O campo estoque deve ser um número inteiro.',
        ],
        'estoque_minimo' => [
            'integer' => 'O campo estoque mínimo deve ser um número inteiro.',
        ],
        'preco_venda' => [
            'numeric' => 'O preço de venda deve ser um valor numérico.',
            'min' => 'O preço de venda deve ser maior ou igual a :min.',
        ],
        'preco_custo' => [
            'numeric' => 'O preço de custo deve ser um valor numérico.',
            'min' => 'O preço de custo deve ser maior ou igual a :min.',
        ],
        'codigo' => [
            'required' => 'O código do produto é obrigatório.',
            'unique' => 'Este código já está em uso.',
        ],
        'nome' => [
            'required' => 'O nome do produto é obrigatório.',
        ],
        'tipo' => [
            'required' => 'O tipo do produto é obrigatório.',
        ],
        'unidade_medida' => [
            'required' => 'A unidade de medida é obrigatória.',
        ],
    ],

    'attributes' => [
        'estoque' => 'estoque',
        'estoque_minimo' => 'estoque mínimo',
        'preco_venda' => 'preço de venda',
        'preco_custo' => 'preço de custo',
        'codigo' => 'código',
        'nome' => 'nome',
        'tipo' => 'tipo',
        'unidade_medida' => 'unidade de medida',
        'categoria' => 'categoria',
        'fornecedor' => 'fornecedor',
        'descricao' => 'descrição',
    ],
]; 