<?php

namespace App\Listeners;

use App\Services\RabbitPublisher;

readonly class PublishUserApproved
{
    public function __construct(private RabbitPublisher $publisher)
    {
    }

    public function handle(object $event): void
    {
        $user = $event->user;

        $this->publisher->publish('accounts.users.approved', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'occurred_at' => now()->toIso8601String(),
            'idempotency_key' => $user->id . '|' . $user->updated_at?->timestamp,
        ]);
    }
}
