<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    public function create(array $data): Payment;
    
    public function getTotalPaidForInvoice(int $invoiceId): float;
    
    public function getPaymentsByInvoiceId(int $invoiceId): Collection;
}
