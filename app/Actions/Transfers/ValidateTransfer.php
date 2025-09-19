<?php

namespace App\Actions\Transfers;

use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Models\Transfer;
use RuntimeException;

class ValidateTransfer
{
    public function execute(Transfer $transfer): bool
    {
        if($transfer->status != TransferStatus::Pending) {
            throw new RuntimeException("Only pending transfers can be validated.");
        }

        if($transfer->fromWallet->balance < $transfer->amount) {
            throw new RuntimeException("Insufficient balance in the source wallet.");
        }

        if($transfer->fromWallet->status == WalletStatus::Inactive) {
            throw new RuntimeException("The source wallet is inactive.");
        }

        if($transfer->toWallet->status == WalletStatus::Inactive) {
            throw new RuntimeException("The destination wallet is inactive.");
        }

        return true;
    }
}
