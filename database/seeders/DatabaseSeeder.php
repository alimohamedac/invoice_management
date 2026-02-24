<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $contracts = \App\Models\Contract::factory(5)->create();
        
        $globalInvoiceSequence = 1;
        
        foreach ($contracts as $contract) {
            $invoiceCount = rand(2, 3);
            
            for ($i = 0; $i < $invoiceCount; $i++) {
                $yearMonth = now()->format('Ym');
                $sequence = str_pad($globalInvoiceSequence++, 3, '0', STR_PAD_LEFT);
                $invoiceNumber = "INV-{$contract->tenant_id}-{$yearMonth}-{$sequence}";
                
                $invoice = \App\Models\Invoice::factory()->create([
                    'contract_id' => $contract->id,
                    'tenant_id' => $contract->tenant_id,
                    'invoice_number' => $invoiceNumber,
                ]);
                
                if (rand(0, 1)) {
                    $paymentCount = rand(1, 2);
                    
                    for ($j = 0; $j < $paymentCount; $j++) {
                        \App\Models\Payment::factory()->create([
                            'invoice_id' => $invoice->id,
                            'tenant_id' => $invoice->tenant_id,
                        ]);
                    }
                }
            }
        }
    }
}
