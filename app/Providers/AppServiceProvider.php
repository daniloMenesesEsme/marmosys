<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ExporterInterface;
use App\Services\Exporters\PDFExporter;
use App\Services\Exporters\ExcelExporter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExporterInterface::class, function ($app) {
            return new PDFExporter();
        });

        $this->app->bind('excel.exporter', function ($app) {
            return new ExcelExporter();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
