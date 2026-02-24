<?php

namespace App\Services;

use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Enums\InvoiceStatus;
use App\Exceptions\ContractNotActiveException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvoiceCancelledException;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Tax\TaxService;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private ContractRepositoryInterface $contractRepo,
        private InvoiceRepositoryInterface $invoiceRepo,
        private PaymentRepositoryInterface $paymentRepo,
        private TaxService $taxService,
    ) {}

    public function createInvoice(CreateInvoiceDTO $dto): Invoice
    {
        $contract = $this->contractRepo->findById($dto->contract_id);

        if (!$this->contractRepo->isActive($dto->contract_id)) {
            throw new ContractNotActiveException();
        }

        $subtotal = (float) $contract->rent_amount;
        $taxAmount = $this->taxService->calculateTotal($subtotal);
        $total = $subtotal + $taxAmount;

        return DB::transaction(function () use ($dto, $subtotal, $taxAmount, $total) {
            $invoiceNumber = $this->invoiceRepo->generateInvoiceNumber($dto->tenant_id);

            return $this->invoiceRepo->create([
                'contract_id' => $dto->contract_id,
                'tenant_id' => $dto->tenant_id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => InvoiceStatus::PENDING,
                'due_date' => $dto->due_date,
                'paid_at' => null,
            ]);
        });
    }

    public function recordPayment(RecordPaymentDTO $dto): Payment
    {
        $invoice = $this->invoiceRepo->findById($dto->invoice_id);

        if ($invoice->status === InvoiceStatus::CANCELLED) {
            throw new InvoiceCancelledException();
        }

        $totalPaid = $this->paymentRepo->getTotalPaidForInvoice($dto->invoice_id);
        $remainingBalance = (float) $invoice->total - $totalPaid;

        if ($dto->amount > $remainingBalance) {
            throw new InsufficientBalanceException();
        }

        return DB::transaction(function () use ($dto, $invoice, $totalPaid) {
            $payment = $this->paymentRepo->create([
                'invoice_id' => $dto->invoice_id,
                'tenant_id' => $dto->tenant_id,
                'amount' => $dto->amount,
                'payment_method' => $dto->payment_method,
                'reference_number' => $dto->reference_number,
                'paid_at' => now(),
            ]);

            $newTotalPaid = $totalPaid + $dto->amount;

            if ($newTotalPaid >= (float) $invoice->total) {
                $invoice->status = InvoiceStatus::PAID;
                $invoice->paid_at = now();
            } else {
                $invoice->status = InvoiceStatus::PARTIALLY_PAID;
            }

            $invoice->save();

            return $payment;
        });
    }

    public function getContractSummary(int $contractId): array
    {
        $invoices = $this->invoiceRepo->getByContractId($contractId);

        $totalInvoiced = $invoices->sum('total');

        $totalPaid = 0;
        foreach ($invoices as $invoice) {
            $totalPaid += $this->paymentRepo->getTotalPaidForInvoice($invoice->id);
        }

        $outstandingBalance = $totalInvoiced - $totalPaid;

        return [
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $outstandingBalance,
            'invoices_count' => $invoices->count(),
            'latest_invoice_date' => $invoices->first()?->created_at?->toDateString(),
        ];
    }
}
