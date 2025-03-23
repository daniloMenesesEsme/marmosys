<?php

namespace App\Interfaces;

interface Itemizable
{
    public function getName(): string;
    public function getPrice(): float;
    public function getType(): string;
    public function getMeasureUnit(): string;
} 