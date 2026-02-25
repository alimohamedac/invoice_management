<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'subtotal' => (float) $this->subtotal,
            'tax_amount' => (float) $this->tax_amount,
            'total' => (float) $this->total,
            'status' => $this->status->value,
            'due_date' => $this->due_date->format('Y-m-d'),
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'remaining_balance' => (float) $this->remaining_balance,
            'contract' => $this->whenLoaded('contract', fn() => ContractResource::make($this->contract)),
            'payments' => $this->whenLoaded('payments', fn() => PaymentResource::collection($this->payments)),
        ];
    }
}
