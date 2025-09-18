<?php

use App\Actions\Accounts\UpdateAccountStatus;
use App\Enums\AccountStatus;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to update account status', function ($startStatus, $endStatus) {
    $account = Account::factory()->create(['status' => $startStatus]);

    (new UpdateAccountStatus())->execute($account, $endStatus);

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => $endStatus
    ]);
})->with([
    AccountStatus::Active->value => [AccountStatus::Active, AccountStatus::Inactive],
    AccountStatus::Inactive->value => [AccountStatus::Inactive, AccountStatus::Active],
]);

it('should not be able to update account status when it already has the status', function ($startStatus, $endStatus) {
    $account = Account::factory()->create(['status' => $startStatus]);

    expect(fn () => (new UpdateAccountStatus())->execute($account, $endStatus))
        ->toThrow(RuntimeException::class, "Account is already '{$endStatus->value}'.");

    $this->assertDatabaseHas('accounts', [
        'id' => $account->id,
        'status' => $startStatus,
    ]);
})->with([
    AccountStatus::Active->value => [AccountStatus::Active, AccountStatus::Active],
    AccountStatus::Inactive->value => [AccountStatus::Inactive, AccountStatus::Inactive],
]);

