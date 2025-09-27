<?php

namespace App\Actions\OrganizationUser;

use App\Events\UserCreated;
use App\Models\OrganizationUser;

class CreateUser
{
    public function execute(array $data): OrganizationUser
    {
        $user = OrganizationUser::create($data);

        event(new UserCreated($user));

        return $user;
    }
}
