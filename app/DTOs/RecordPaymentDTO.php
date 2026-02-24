<?php

namespace App\DTOs;

readonly class RecordPaymentDTO
{
    public function __construct(
        public int $invoice_id,
        public float $amount,
        public string $payment_method,
        public ?string $reference_number,
        public int $tenant_id,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            invoice_id: $request->validated('invoice_id'),
            amount: $request->validated('amount'),
            payment_method: $request->validated('payment_method'),
            reference_number: $request->validated('reference_number'),
            tenant_id: $request->user()->tenant_id,
        );
    }
}
