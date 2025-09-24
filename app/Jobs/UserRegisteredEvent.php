<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UserRegisteredEvent implements ShouldQueue
{
    use Queueable;

    public $connection = 'rabbitmq';

    public function __construct(public array $payload)
    {
    }

    public function handle(): void
    {
        Log::info("[UserRegisteredEvent] Payload: " . json_encode($this->payload));
    }
}
