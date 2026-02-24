<?php

namespace App\Repositories\Eloquent;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function findById(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function findByIdForTenant(int $id, int $tenantId): ?Invoice
    {
        return Invoice::where('id', $id)->where('tenant_id', $tenantId)->first();
    }

    public function getByContractId(int $contractId): Collection
    {
        return Invoice::where('contract_id', $contractId)->orderBy('created_at', 'desc')->get();
    }

    public function generateInvoiceNumber(int $tenantId): string
    {
        $yearMonth = now()->format('Ym');
        $paddedTenantId = str_pad($tenantId, 3, '0', STR_PAD_LEFT);

        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', "INV-{$paddedTenantId}-{$yearMonth}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice->invoice_number, -4);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        $paddedSequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return "INV-{$paddedTenantId}-{$yearMonth}-{$paddedSequence}";
    }

    public function updateStatus(int $invoiceId, InvoiceStatus $status): bool
    {
        $invoice = $this->findById($invoiceId);

        $invoice->status = $status;

        return $invoice->save();
    }
}
