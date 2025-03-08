<?php

namespace App\Enums;

enum ProductType: string
{
    case PRODUCT = 'product';
    case MATERIAL = 'material';
    case SERVICE = 'service';
    case SUPPLY = 'supply';

    public function label(): string
    {
        return match($this) {
            self::PRODUCT => 'Produto',
            self::MATERIAL => 'Material',
            self::SERVICE => 'Serviço Mão de Obra',
            self::SUPPLY => 'Insumos'
        };
    }

    public function prefix(): string
    {
        return match($this) {
            self::PRODUCT => 'PRD',
            self::MATERIAL => 'MTR',
            self::SERVICE => 'SRV',
            self::SUPPLY => 'INS'
        };
    }
} 