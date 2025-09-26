<?php

namespace App\Actions\Transfers;

use App\Enums\TransferStatus;
use App\Events\TransferCompleted;
use App\Events\TransferFailed;
use App\Exceptions\Wallets\DestinationWalletInactiveException;
use App\Exceptions\Wallets\InsufficientBalanceInWalletToTransferException;
use App\Exceptions\Wallets\InvalidTransferStatusException;
use App\Exceptions\Wallets\SourceWalletInactiveException;
use App\Models\Transfer;

class ProcessTransfer
{
    public function execute(Transfer $transfer): void
    {
        try {
            $transferValidation = (new ValidateTransfer)->execute($transfer);

            if ($transferValidation) {
                $transfer->fromWallet->decrement('balance', $transfer->amount);
                $transfer->toWallet->increment('balance', $transfer->amount);

                $transfer->update(['status' => TransferStatus::Completed]);

                event(new TransferCompleted($transfer));

                return;
            }
        } catch (InsufficientBalanceInWalletToTransferException|DestinationWalletInactiveException|SourceWalletInactiveException) {
            $transfer->update(['status' => TransferStatus::Failed]);

            event(new TransferFailed($transfer));
        } catch (InvalidTransferStatusException $exception) {
            report($exception);
        }
    }
}
