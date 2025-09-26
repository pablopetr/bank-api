<?php

namespace App\Actions\Accounts;

use App\Enums\AccountStatus;
use App\Models\Account;

class DeactivateAccount
{
    public function execute(int $accountId): void
    {
        $account = Account::query()->findOrFail($accountId);

        if ($account->status === AccountStatus::Inactive) {
            throw new \RuntimeException('Account is already inactive.');
        }

        $account->update([
            'status' => AccountStatus::Inactive,
        ]);
    }
}
