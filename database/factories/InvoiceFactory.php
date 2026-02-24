<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $sequence = 1;
        $tenantId = fake()->numberBetween(1, 10);
        $yearMonth = now()->format('Ym');
        $invoiceNumber = "INV-{$tenantId}-{$yearMonth}-" . str_pad($sequence++, 3, '0', STR_PAD_LEFT);
        
        $subtotal = fake()->randomFloat(2, 500, 5000);
        $taxAmount = round($subtotal * 0.175, 2);
        $total = $subtotal + $taxAmount;
        
        return [
            'contract_id' => \App\Models\Contract::factory(),
            'tenant_id' => $tenantId,
            'invoice_number' => $invoiceNumber,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => \App\Enums\InvoiceStatus::PENDING,
            'due_date' => now()->addDays(30),
            'paid_at' => null,
        ];
    }
}
