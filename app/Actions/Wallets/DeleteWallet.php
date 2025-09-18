<?php

namespace App\Actions\Wallets;

use App\Enums\WalletType;
use App\Models\Wallet;
use RuntimeException;

class DeleteWallet
{
    public function execute(int $walletId): void
    {
        $wallet = Wallet::query()->findOrFail($walletId);

        if ($wallet->balance > 0) {
            throw new RuntimeException('Cannot delete a wallet with a positive balance.');
        }

        if ($wallet->type === WalletType::Default) {
            throw new RuntimeException('Cannot delete the default wallet.');
        }

        $wallet->delete();
    }
}
