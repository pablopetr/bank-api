<?php

namespace App\Observers;

use App\Actions\Accounts\CreateAccount;
use App\Enums\UserStatus;
use App\Models\OrganizationUser;

class OrganizationUserObserver
{
    public function updated(OrganizationUser $organizationUser): void
    {
        if ($organizationUser->wasChanged('status') && $organizationUser->status === UserStatus::Approved) {
            (new CreateAccount)->execute($organizationUser);
        }
    }
}
