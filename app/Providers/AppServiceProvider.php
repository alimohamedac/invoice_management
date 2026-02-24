<?php

namespace App\Providers;

use App\Services\Tax\MunicipalFeeCalculator;
use App\Services\Tax\TaxService;
use App\Services\Tax\VatCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaxService::class, function ($app) {
            return new TaxService([
                $app->make(VatCalculator::class),
                $app->make(MunicipalFeeCalculator::class),
            ]);
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
