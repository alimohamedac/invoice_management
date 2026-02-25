<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListInvoicesRequest;
use App\Http\Requests\RecordPaymentRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Resources\ContractSummaryResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentResource;
use App\Models\Contract;
use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function store(StoreInvoiceRequest $request, Contract $contract)
    {
        $this->authorize('create', [Invoice::class, $contract]); //for policy

        $dto = new CreateInvoiceDTO(
            contract_id: $request->validated('contract_id'),
            due_date: $request->validated('due_date'),
            tenant_id: $contract->tenant_id
        );

        $invoice = $this->invoiceService->createInvoice($dto);

        return InvoiceResource::make($invoice)->response()->setStatusCode(201);
    }

    public function index(ListInvoicesRequest $request, Contract $contract)
    {
        $query = Invoice::where('contract_id', $contract->id);

        if ($request->has('status')) {
            $query->where('status', $request->validated('status'));
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->validated('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->validated('date_to'));
        }

        $perPage = $request->validated('per_page', 15);
        $invoices = $query->paginate($perPage);

        return InvoiceResource::collection($invoices);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['payments', 'contract']);

        return InvoiceResource::make($invoice);
    }

    public function recordPayment(RecordPaymentRequest $request, Invoice $invoice)
    {
        $this->authorize('recordPayment', $invoice);

        $dto = new RecordPaymentDTO(
            invoice_id: $request->validated('invoice_id'),
            amount: $request->validated('amount'),
            payment_method: $request->validated('payment_method'),
            reference_number: $request->validated('reference_number'),
            tenant_id: $invoice->tenant_id
        );

        $payment = $this->invoiceService->recordPayment($dto);

        return PaymentResource::make($payment)
            ->response()
            ->setStatusCode(201);
    }

    public function summary(Contract $contract)
    {
        $summary = $this->invoiceService->getContractSummary($contract->id);
        $summary['contract_id'] = $contract->id;

        return ContractSummaryResource::make($summary);
    }
}
