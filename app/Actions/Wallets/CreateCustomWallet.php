<?php

namespace App\Actions\Wallets;

use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;

class CreateCustomWallet
{
    public function execute(Account $account, string $walletName): void
    {
        Wallet::query()->create([
            'name' => $walletName,
            'account_id' => $account->id,
            'balance' => 0.0,
            'type' => WalletType::Wallet->value,
        ]);
    }
}
