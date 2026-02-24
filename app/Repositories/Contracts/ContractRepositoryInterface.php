<?php

namespace App\Repositories\Contracts;

use App\Models\Contract;

interface ContractRepositoryInterface
{
    public function findById(int $id): ?Contract;
    
    public function findByIdForTenant(int $id, int $tenantId): ?Contract;
    
    public function isActive(int $contractId): bool;
}
