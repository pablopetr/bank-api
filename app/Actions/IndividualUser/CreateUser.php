<?php

namespace App\Actions\IndividualUser;

use App\Events\UserCreated;
use App\Models\IndividualUser;

class CreateUser
{
    public function execute(array $data): IndividualUser
    {
        $user = IndividualUser::create($data);

        event(new UserCreated($user));

        return $user;
    }
}
