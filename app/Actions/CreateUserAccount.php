<?php

namespace App\Actions;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class CreateUserAccount
{
    public function execute(Model $user): void
    {

        Account::query()->create([
            'accountable_id' => $user->id,
            'accountable_type' => get_class($user),
            'status' => 'active',
        ]);
    }
}
