<?php

namespace App\Observers;

use App\Actions\Accounts\CreateAccount;
use App\Enums\UserStatus;
use App\Models\IndividualUser;

class IndividualUserObserver
{
    public function updated(IndividualUser $individualUser): void
    {
        if ($individualUser->wasChanged('status') && $individualUser->status === UserStatus::Approved) {
            (new CreateAccount)->execute($individualUser);
        }
    }
}
