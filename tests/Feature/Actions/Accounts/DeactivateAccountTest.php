<?php

use App\Actions\Accounts\DeactivateAccount;
use App\Enums\AccountStatus;
use App\Models\Account;

it('should be able to deactivate account', function () {
    $account = Account::factory()->create(['status' => AccountStatus::Active]);

    (new DeactivateAccount())->execute($account->id);

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => AccountStatus::Inactive->value,
    ]);
});

it('should not be able to deactivate an already inactive account', function () {
    $account = Account::factory()->create(['status' => AccountStatus::Inactive]);

    expect(fn () => (new DeactivateAccount())->execute($account->id))
        ->toThrow(RuntimeException::class, "Account is already inactive.");

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => AccountStatus::Inactive->value,
    ]);
});
