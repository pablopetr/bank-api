<?php

namespace App\Jobs\IndividualUser;

use App\Enums\UserStatus;
use App\Models\IndividualUser;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUsersStatusJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public array $userIds, public UserStatus $status)
    {
    }

    public function handle(): void
    {
        $users = IndividualUser::whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            $user->status = $this->status->value;
            $user->save();
        }
    }
}
