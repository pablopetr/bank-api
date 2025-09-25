<?php

namespace App\Listeners;

use App\Services\RabbitPublisher;

readonly class PublishTransferCompleted
{
    public function __construct(private RabbitPublisher $publisher)
    {
    }

    public function handle(object $event): void
    {
        $transfer = $event->transfer;

        $this->publisher->publish('payments.transfers.completed', [
            'transfer_id'    => $transfer->id,
            'status'         => (string) $transfer->status,
            'amount'         => (string) $transfer->amount,
            'from_account'   => $transfer->from_wallet_id,
            'to_account'     => $transfer->to_wallet_id,
            'occurred_at'    => now()->toIso8601String(),
            'idempotency_key'=> $transfer->id.'|'.$transfer->updated_at?->timestamp,
        ]);
    }
}
