<?php

namespace App\Jobs\OrganizationUser;

use App\Enums\UserStatus;
use App\Models\OrganizationUser;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUsersStatusJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public function __construct(public array $userIds, public UserStatus $status)
    {
    }

    public function handle(): void
    {
        $users = OrganizationUser::whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            $user->status = $this->status->value;
            $user->save();
        }
    }
}
