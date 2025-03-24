<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ExporterInterface;
use App\Services\Exporters\PDFExporter;
use App\Services\Exporters\ExcelExporter;
use Illuminate\Pagination\Paginator;

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

        // Carregar nossos helpers
        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.materialize');
        Paginator::defaultSimpleView('vendor.pagination.materialize');
    }
}
