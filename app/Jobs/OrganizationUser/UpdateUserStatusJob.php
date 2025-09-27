<?php

namespace App\Jobs\OrganizationUser;

use App\Enums\UserStatus;
use App\Events\UserApproved;
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
        $user = OrganizationUser::where('id', $this->userId)
            ->firstOrFail();

        $user->update(['status' => $this->status->value]);

        if ($this->status == UserStatus::Approved) {
            event(new UserApproved($user));
        }
    }
}
