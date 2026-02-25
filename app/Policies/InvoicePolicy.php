<?php

namespace App\Policies;

use App\Enums\InvoiceStatus;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function create(User $user, Contract $contract): bool
    {
        return $contract->tenant_id === $user->tenant_id;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $invoice->tenant_id === $user->tenant_id;
    }

    public function recordPayment(User $user, Invoice $invoice): bool
    {
        return $invoice->tenant_id === $user->tenant_id &&
               $invoice->status !== InvoiceStatus::CANCELLED;
    }
}
