<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function getTotalPaidForInvoice(int $invoiceId): float
    {
        return Payment::where('invoice_id', $invoiceId)->sum('amount');
    }

    public function getPaymentsByInvoiceId(int $invoiceId): Collection
    {
        return Payment::where('invoice_id', $invoiceId)->orderBy('paid_at', 'desc')->get();
    }
}
