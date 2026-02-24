<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientBalanceException extends Exception
{
    protected $message = 'Payment amount exceeds remaining invoice balance';
    
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'error' => 'insufficient_balance',
        ], 422);
    }
}
