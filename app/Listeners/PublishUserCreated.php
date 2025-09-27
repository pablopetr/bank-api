<?php

namespace App\Listeners;

use App\Services\RabbitPublisher;

class PublishUserCreated
{
    public function __construct(public RabbitPublisher $publisher) {}

    public function handle(object $event): void
    {
        $user = $event->user;

        $this->publisher->publish('accounts.users.created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'occurred_at' => now()->toIso8601String(),
            'idempotency_key' => $user->id.'|'.$user->updated_at?->timestamp,
        ]);
    }
}
