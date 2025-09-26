<?php

namespace App\Listeners;

use App\Services\RabbitPublisher;

class PublishedTransferFailed
{
    public function __construct(public RabbitPublisher $publisher) {}

    public function handle(object $event): void
    {
        $transfer = $event->transfer;
        $transfer->loadMissing(['fromWallet.account.accountable']);

        $this->publisher->publish('payments.transfers.failed', [
            'event_id' => (string) $transfer,
            'from_user_email' => $transfer->fromWallet->account->accountable->email,
            'from_user_name' => $transfer->fromWallet->account->accountable->name,
            'from_account_number' => $transfer->fromWallet->account->number,
            'to_account_number' => $transfer->toWallet->account->number,
            'transfer_id' => $transfer->id,
            'status' => $transfer->status->value,
            'amount' => (string) $transfer->amount,
            'from_account' => $transfer->from_wallet_id,
            'occurred_at' => now()->toIso8601String(),
            'idempotency_key' => $transfer->id.'|'.$transfer->updated_at?->timestamp,
        ]);
    }
}
