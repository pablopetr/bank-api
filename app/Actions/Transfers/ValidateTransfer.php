<?php

namespace App\Actions\Transfers;

use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Exceptions\Wallets\DestinationWalletInactiveException;
use App\Exceptions\Wallets\InsufficientBalanceToTransferException;
use App\Exceptions\Wallets\SourceWalletInactiveException;
use App\Models\Transfer;
use RuntimeException;

class ValidateTransfer
{
    /**
     * @throws InsufficientBalanceToTransferException
     * @throws SourceWalletInactiveException
     * @throws DestinationWalletInactiveException
     */
    public function execute(Transfer $transfer): bool
    {
        if($transfer->status != TransferStatus::Pending) {
            throw new RuntimeException("Only pending transfers can be validated.");
        }

        if($transfer->fromWallet->balance < $transfer->amount) {
            throw new InsufficientBalanceToTransferException();
        }

        if($transfer->fromWallet->status == WalletStatus::Inactive) {
            throw new SourceWalletInactiveException();
        }

        if($transfer->toWallet->status == WalletStatus::Inactive) {
            throw new DestinationWalletInactiveException();
        }

        return true;
    }
}
