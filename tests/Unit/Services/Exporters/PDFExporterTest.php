<?php

namespace Tests\Unit\Services\Exporters;

use Tests\TestCase;
use App\Services\Exporters\PDFExporter;
use Illuminate\Support\Collection;

class PDFExporterTest extends TestCase
{
    private PDFExporter $exporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exporter = new PDFExporter();
    }

    public function test_can_set_data()
    {
        $data = new Collection();
        $result = $this->exporter->setData($data);

        $this->assertInstanceOf(PDFExporter::class, $result);
    }

    public function test_can_set_template()
    {
        $result = $this->exporter->setTemplate('reports.financial');

        $this->assertInstanceOf(PDFExporter::class, $result);
    }
} 