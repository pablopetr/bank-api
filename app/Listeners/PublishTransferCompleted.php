<?php

namespace App\Listeners;

use App\Services\RabbitPublisher;

readonly class PublishTransferCompleted
{
    public function __construct(private RabbitPublisher $publisher) {}

    public function handle(object $event): void
    {
        $transfer = $event->transfer;
        $transfer->loadMissing(['fromWallet.account.accountable', 'toWallet.account.accountable']);

        $this->publisher->publish('payments.transfers.completed', [
            'from_user_email' => $transfer->fromWallet->account->accountable->email,
            'to_user_email' => $transfer->toWallet->account->accountable->email,
            'from_user_name' => $transfer->fromWallet->account->accountable->name,
            'to_user_name' => $transfer->toWallet->account->accountable->name,
            'from_account_number' => $transfer->fromWallet->account->number,
            'to_account_number' => $transfer->toWallet->account->number,
            'transfer_id' => $transfer->id,
            'status' => $transfer->status->value,
            'amount' => (string) $transfer->amount,
            'from_account' => $transfer->from_wallet_id,
            'to_account' => $transfer->to_wallet_id,
            'occurred_at' => now()->toIso8601String(),
            'idempotency_key' => $transfer->id.'|'.$transfer->updated_at?->timestamp,
        ]);
    }
}
