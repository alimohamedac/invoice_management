<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ContractNotActiveException extends Exception
{
    protected $message = 'Cannot create invoice for inactive contract';

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message, 'error' => 'contract_not_active',], 422);
    }
}
