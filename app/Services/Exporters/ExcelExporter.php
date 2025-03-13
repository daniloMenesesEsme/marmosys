<?php

namespace App\Services\Exporters;

use App\Contracts\ExporterInterface;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class ExcelExporter implements ExporterInterface
{
    private $data;
    private $template;

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function download(string $filename)
    {
        return Excel::download(
            new TransactionsExport($this->data), 
            $filename
        );
    }
} 