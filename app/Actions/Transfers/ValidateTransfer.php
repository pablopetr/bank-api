<?php

namespace App\Actions\Transfers;

use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Exceptions\Wallets\DestinationWalletInactiveException;
use App\Exceptions\Wallets\InsufficientBalanceInWalletToTransferException;
use App\Exceptions\Wallets\InvalidTransferStatusException;
use App\Exceptions\Wallets\SourceWalletInactiveException;
use App\Models\Transfer;
use RuntimeException;

class ValidateTransfer
{
    /**
     * @throws InsufficientBalanceInWalletToTransferException
     * @throws SourceWalletInactiveException
     * @throws DestinationWalletInactiveException
     * @throws InvalidTransferStatusException
     */
    public function execute(Transfer $transfer): bool
    {
        if($transfer->status != TransferStatus::Pending) {
            throw new InvalidTransferStatusException();
        }

        if($transfer->fromWallet->balance < $transfer->amount) {
            throw new InsufficientBalanceInWalletToTransferException();
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
