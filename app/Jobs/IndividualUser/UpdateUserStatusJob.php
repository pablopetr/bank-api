<?php

namespace App\Jobs\IndividualUser;

use App\Enums\UserStatus;
use App\Models\IndividualUser;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUserStatusJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public function __construct(public int $userId, public UserStatus $status) {}

    public function handle(): void
    {
        IndividualUser::where('id', $this->userId)
            ->update(['status' => $this->status->value]);
    }
}
