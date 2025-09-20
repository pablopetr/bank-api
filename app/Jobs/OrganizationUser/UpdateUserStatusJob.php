<?php

namespace App\Jobs\OrganizationUser;

use App\Enums\UserStatus;
use App\Models\OrganizationUser;
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
        OrganizationUser::where('id', $this->userId)
            ->update(['status' => $this->status->value]);
    }
}
