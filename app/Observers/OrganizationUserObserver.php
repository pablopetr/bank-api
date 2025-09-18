<?php

namespace App\Observers;

use App\Actions\CreateUserAccount;
use App\Enums\UserStatus;
use App\Models\OrganizationUser;

class OrganizationUserObserver
{
    public function created(OrganizationUser $organizationUser): void
    {
        //
    }

    public function updated(OrganizationUser $organizationUser): void
    {
        if ($organizationUser->wasChanged('status') && $organizationUser->status === UserStatus::Approved) {
            (new CreateUserAccount)->execute($organizationUser);
        }
    }

    public function deleted(OrganizationUser $organizationUser): void
    {
        //
    }

    public function restored(OrganizationUser $organizationUser): void
    {
        //
    }

    public function forceDeleted(OrganizationUser $organizationUser): void
    {
        //
    }
}
