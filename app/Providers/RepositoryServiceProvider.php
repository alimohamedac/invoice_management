<?php

namespace App\Providers;

use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Eloquent\EloquentContractRepository;
use App\Repositories\Eloquent\EloquentInvoiceRepository;
use App\Repositories\Eloquent\EloquentPaymentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContractRepositoryInterface::class, EloquentContractRepository::class);

        $this->app->bind(InvoiceRepositoryInterface::class, EloquentInvoiceRepository::class);

        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
