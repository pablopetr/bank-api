<?php

namespace App\Actions\Accounts;

use App\Models\Account;

class DeleteAccount
{
    public function execute(int $accountId): void
    {
        $account = Account::query()->findOrFail($accountId);

        $availableBalance = $account->wallets()->sum('balance');

        if ($availableBalance > 0) {
            throw new \RuntimeException('Cannot delete an account with a positive balance in its wallets.');
        }

        $account->delete();
    }
}
