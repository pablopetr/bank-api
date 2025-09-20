<?php

namespace App\Actions\Accounts;

use App\Enums\AccountStatus;
use App\Models\Account;

class ActivateAccount
{
    public function execute(int $accountId): void
    {
        $account = Account::query()->findOrFail($accountId);

        if ($account->status === AccountStatus::Active) {
            throw new \RuntimeException("Account is already active.");
        }

        $account->update([
            'status' => AccountStatus::Active,
        ]);
    }
}
