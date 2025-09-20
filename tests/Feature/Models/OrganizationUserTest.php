<?php

use App\Enums\UserStatus;
use App\Models\Account;
use App\Models\OrganizationUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create an organization user', function () {
    $organizationUser = OrganizationUser::create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password'),
        'status' => UserStatus::WaitingForApproval,
    ]);

    expect($organizationUser)->toBeInstanceOf(OrganizationUser::class)
        ->and($organizationUser->name)->toBe('John Doe')
        ->and($organizationUser->email)->toBe('johndoe@example.com')
        ->and($organizationUser->status)->toBe(UserStatus::WaitingForApproval);
});

it('should not be able to create an organization user with invalid status', function () {
    OrganizationUser::create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password'),
        'status' => 'invalid-status',
    ]);
})->throws(ValueError::class);

it('should be able to get accounts relationship', function () {
    $organizationUser = OrganizationUser::factory()->create();

    $accounts = Account::factory()->count(3)->create([
        'accountable_id' => $organizationUser->id,
        'accountable_type' => OrganizationUser::class,
    ]);

    expect($organizationUser->accounts)->toHaveCount(3)
        ->and($organizationUser->accounts->first())->toBeInstanceOf(Account::class)
        ->and($organizationUser->accounts->pluck('id')->toArray())->toEqual($accounts->pluck('id')->toArray());
});
