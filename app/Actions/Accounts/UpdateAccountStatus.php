<?php

namespace App\Actions\Accounts;

use App\Enums\AccountStatus;
use App\Models\Account;
use RuntimeException;

class UpdateAccountStatus
{
    public function execute(Account $account, AccountStatus $status): Account
    {
        if($account->status === $status) {
            throw new RuntimeException("Account is already '{$status->value}'.");
        }

        $account->update([
            'status' => $status,
        ]);

        return $account;
    }
}
