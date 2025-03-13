<?php

namespace App\Services\Exporters;

use App\Contracts\ExporterInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFExporter implements ExporterInterface
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
        $pdf = PDF::loadView("exports.{$this->template}", [
            'data' => $this->data,
            'totalizadores' => app(ReportService::class)->getTotalizadores($this->data)
        ]);

        return $pdf->download($filename);
    }
} 