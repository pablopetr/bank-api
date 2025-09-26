<?php

use App\Enums\AccountStatus;
use App\Enums\UserStatus;
use App\Models\OrganizationUser;

it('should create an account when an organization user is approved', function () {
    $organizationUser = OrganizationUser::factory()->create([
        'status' => UserStatus::WaitingForApproval,
    ]);

    expect($organizationUser->accounts)->toBeEmpty();

    $organizationUser->update(['status' => UserStatus::Approved]);

    $organizationUser->refresh();

    expect($organizationUser->accounts->first())->not->toBeNull()
        ->and($organizationUser->accounts->first()->number)->toBeInt()
        ->and($organizationUser->accounts->first()->status)->toBe(AccountStatus::Active);
});
