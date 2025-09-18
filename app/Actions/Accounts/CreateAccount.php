<?php

namespace App\Actions\Accounts;

use App\Enums\AccountStatus;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateAccount
{
    public function execute(Model $user): Account
    {
        return DB::transaction(function () use ($user) {
            $lastAccount = Account::query()->select('number')->latest('number')->first();

            return Account::query()->create([
                'number' => $lastAccount ? $lastAccount->number + 1 : Account::INITIAL_NUMBER,
                'accountable_id' => $user->id,
                'accountable_type' => get_class($user),
                'status' => AccountStatus::Active,
            ]);
        });
    }
}
