<?php

namespace App\Contracts;

interface ExporterInterface
{
    public function setData($data): self;
    public function setTemplate(string $template): self;
    public function download(string $filename);
} 