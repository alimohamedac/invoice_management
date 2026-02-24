<?php

namespace App\DTOs;

readonly class CreateInvoiceDTO
{
    public function __construct(
        public int $contract_id,
        public string $due_date,
        public int $tenant_id,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            contract_id: $request->validated('contract_id'),
            due_date: $request->validated('due_date'),
            tenant_id: $request->user()->tenant_id,
        );
    }
}
