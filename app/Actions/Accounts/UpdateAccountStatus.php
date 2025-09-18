<?php

namespace App\Actions\Accounts;

use App\Enums\AccountStatus;
use App\Enums\WalletStatus;
use App\Models\Account;
use RuntimeException;

class UpdateAccountStatus
{
    public function execute(Account $account, AccountStatus $status): Account
    {
        if($account->status === $status) {
            throw new RuntimeException("Account is already '{$status->value}'.");
        }

        if($status === AccountStatus::Inactive) {
            $availableBalance = $account->wallets()
                ->sum('balance');

            if($availableBalance > 0) {
                throw new RuntimeException("Cannot deactivate account with wallets having a positive balance.");
            }
        }

        $account->update([
            'status' => $status,
        ]);

        return $account;
    }
}
