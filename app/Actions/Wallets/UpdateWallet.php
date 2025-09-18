<?php

namespace App\Actions\Wallets;

use App\Enums\WalletType;
use App\Models\Wallet;
use RuntimeException;

class UpdateWallet
{
    public function execute(Wallet $wallet, string $name): Wallet
    {
        if ($wallet->type === WalletType::Default) {
            throw new RuntimeException('You cannot update the default wallet.');
        }

        $wallet->update([
            'name' => $name,
        ]);

        return $wallet;
    }
}
