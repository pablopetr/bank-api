<?php

namespace App\Observers;

use App\Actions\CreateUserAccount;
use App\Enums\UserStatus;
use App\Models\IndividualUser;

class IndividualUserObserver
{
    public function created(IndividualUser $individualUser): void
    {
        //
    }

    public function updated(IndividualUser $individualUser): void
    {
        if ($individualUser->wasChanged('status') && $individualUser->status === UserStatus::Approved) {
            (new CreateUserAccount())->execute($individualUser);
        }
    }

    public function deleted(IndividualUser $individualUser): void
    {
        //
    }

    public function restored(IndividualUser $individualUser): void
    {
        //
    }

    public function forceDeleted(IndividualUser $individualUser): void
    {
        //
    }
}
