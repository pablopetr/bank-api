<?php

namespace App\Actions\Transfers;

use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use RuntimeException;

class CreateTransfer
{
    public function execute(Wallet $fromWallet, Wallet $toWallet, float $amount): Transfer
    {
        if($fromWallet->id === $toWallet->id) {
            throw new RuntimeException('Cannot transfer to the same wallet.');
        }

        if($fromWallet->status == WalletStatus::Inactive) {
            throw new RuntimeException('The source wallet is inactive.');
        }

        if($toWallet->status == WalletStatus::Inactive) {
            throw new RuntimeException('The destination wallet is inactive.');
        }

        if($amount <= 0) {
            throw new RuntimeException('The transfer amount must be greater than zero.');
        }

        return Transfer::create([
            'from_wallet_id' => $fromWallet->id,
            'to_wallet_id' => $toWallet->id,
            'amount' => $amount,
            'status' => TransferStatus::Pending->value,
        ]);
    }
}
