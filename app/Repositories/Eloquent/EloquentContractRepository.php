<?php

namespace App\Repositories\Eloquent;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;

class EloquentContractRepository implements ContractRepositoryInterface
{
    public function findById(int $id): ?Contract
    {
        return Contract::find($id);
    }

    public function findByIdForTenant(int $id, int $tenantId): ?Contract
    {
        return Contract::where('id', $id)->where('tenant_id', $tenantId)->first();
    }

    public function isActive(int $contractId): bool
    {
        $contract = $this->findById($contractId);

        return $contract && $contract->status === ContractStatus::ACTIVE;
    }
}
