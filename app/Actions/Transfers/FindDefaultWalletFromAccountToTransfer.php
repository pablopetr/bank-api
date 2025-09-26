<?php

namespace App\Actions\Transfers;

use App\Enums\AccountStatus;
use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;

class FindDefaultWalletFromAccountToTransfer
{
    public function execute(int $accountNumber): Wallet
    {
        $account = Account::query()->where('number', $accountNumber)->firstOrFail();

        if ($account->status == AccountStatus::Inactive) {
            throw new \RuntimeException('Account is inactive.');
        }

        $defaultWallet = $account->wallets()->where('type', WalletType::Default->value)->first();

        if (! $defaultWallet) {
            throw new \RuntimeException('Default wallet not found for the account.');
        }

        return $defaultWallet;
    }
}
