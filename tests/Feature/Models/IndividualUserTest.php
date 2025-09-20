<?php

use App\Enums\UserStatus;
use App\Models\Account;
use App\Models\IndividualUser;

it('should be able to create an individual user', function (UserStatus $status) {
    $individualUser = IndividualUser::create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password'),
        'status' => $status,
    ]);

    expect($individualUser)->toBeInstanceOf(IndividualUser::class)
        ->and($individualUser->name)->toBe('John Doe')
        ->and($individualUser->email)->toBe('johndoe@example.com')
        ->and($individualUser->status)->toBe($status);
})->with(function () {
    return collect(UserStatus::cases())
        ->mapWithKeys(function (UserStatus $status) {
            return [$status->value => [$status]];
        });
});

it('should not be able to create an individual user with invalid status', function () {
    IndividualUser::create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password'),
        'status' => 'invalid-status',
    ]);
})->throws(ValueError::class);

it('should be able to get accounts relationship', function () {
    $individualUser = IndividualUser::factory()->create();

    $accounts = Account::factory()->count(3)->create([
        'accountable_id' => $individualUser->id,
        'accountable_type' => IndividualUser::class,
    ]);

    expect($individualUser->accounts)->toHaveCount(3)
        ->and($individualUser->accounts->first())->toBeInstanceOf(Account::class)
        ->and($individualUser->accounts->pluck('id')->toArray())->toEqual($accounts->pluck('id')->toArray());
});
