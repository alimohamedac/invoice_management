<?php

namespace App\Repositories\Contracts;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface
{
    public function create(array $data): Invoice;
    
    public function findById(int $id): ?Invoice;
    
    public function findByIdForTenant(int $id, int $tenantId): ?Invoice;
    
    public function getByContractId(int $contractId): Collection;
    
    public function generateInvoiceNumber(int $tenantId): string;
    
    public function updateStatus(int $invoiceId, InvoiceStatus $status): bool;
}
