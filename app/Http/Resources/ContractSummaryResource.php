<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'contract_id' => $this->resource['contract_id'],
            'total_invoiced' => (float) $this->resource['total_invoiced'],
            'total_paid' => (float) $this->resource['total_paid'],
            'outstanding_balance' => (float) $this->resource['outstanding_balance'],
            'invoices_count' => (int) $this->resource['invoices_count'],
            'latest_invoice_date' => $this->resource['latest_invoice_date'],
        ];
    }
}
