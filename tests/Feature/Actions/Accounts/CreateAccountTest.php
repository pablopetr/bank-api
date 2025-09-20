<?php

use App\Actions\Accounts\CreateAccount;
use App\Models\Account;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;

it('should be able to create an account', function (string $modelClass) {
    /** @var $modelClass IndividualUser|OrganizationUser */
    $user = $modelClass::factory()->create();

    $account = (new CreateAccount)->execute($user);

    $this->assertInstanceOf(Account::class, $account);

    $this->assertDatabaseHas(Account::class, [
        'accountable_type' => $modelClass,
        'accountable_id' => $user->id,
        'number' => Account::INITIAL_NUMBER,
    ]);
})->with(function () {
    return [
        'Organization User' => [OrganizationUser::class],
        'Individual User' => [IndividualUser::class],
    ];
});

it('should be able to create account with sequential number', function (string $modelClass) {
    /** @var $modelClass IndividualUser|OrganizationUser */
    $user = $modelClass::factory()->create();

    (new CreateAccount)->execute($user);

    $account = (new CreateAccount)->execute($user);

    $this->assertInstanceOf(Account::class, $account);

    $this->assertDatabaseCount(Account::class, 2);
    $this->assertDatabaseHas(Account::class, [
        'accountable_type' => $modelClass,
        'accountable_id' => $user->id,
        'number' => Account::INITIAL_NUMBER + 1,
    ]);
})->with(function () {
    return [
        'Organization User' => [OrganizationUser::class],
        'Individual User' => [IndividualUser::class],
    ];
});
