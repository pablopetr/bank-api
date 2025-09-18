<?php

namespace App\Actions\Wallets;

use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;
use RuntimeException;

class CreateCustomWallet
{
    public function execute(Account $account, string $walletName): Wallet
    {
        $existentWallet = Wallet::query()
            ->where('account_id', $account->id)
            ->where('name', $walletName)
            ->first();

        if ($existentWallet) {
            throw new RuntimeException("A wallet with name '{$walletName}' already exists in the account.");
        }

        return Wallet::query()->create([
            'name' => $walletName,
            'account_id' => $account->id,
            'balance' => 0.0,
            'type' => WalletType::Wallet->value,
        ]);
    }
}
