<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvoiceCancelledException extends Exception
{
    protected $message = 'Cannot record payment for cancelled invoice';
    
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'error' => 'invoice_cancelled',
        ], 422);
    }
}
