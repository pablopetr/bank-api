<?php

namespace App\Actions\Wallets;

use App\Enums\WalletStatus;
use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;

class CreateDefaultWallet
{
    public function execute(Account $account): Wallet
    {
        return Wallet::query()->create([
            'name' => Wallet::DEFAULT_WALLET_NAME,
            'balance' => 0,
            'type' => WalletType::Default,
            'status' => WalletStatus::Active->value,
            'account_id' => $account->id,
        ]);
    }
}
