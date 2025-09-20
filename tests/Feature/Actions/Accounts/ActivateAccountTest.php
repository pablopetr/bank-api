<?php

use App\Actions\Accounts\ActivateAccount;
use App\Enums\AccountStatus;
use App\Models\Account;

it('should be able to activate account', function () {
    $account = Account::factory()->create(['status' => AccountStatus::Inactive]);

    (new ActivateAccount())->execute($account->id);

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => AccountStatus::Active->value,
    ]);
});

it('should not be able to activate an account when it is already active', function () {
    $account = Account::factory()->create(['status' => AccountStatus::Active]);

    expect(fn () => (new ActivateAccount())->execute($account->id))
        ->toThrow(RuntimeException::class, "Account is already active.");

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => AccountStatus::Active->value,
    ]);
});
