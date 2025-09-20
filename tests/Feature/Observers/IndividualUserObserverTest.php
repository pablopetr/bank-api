<?php

use App\Enums\UserStatus;
use App\Models\IndividualUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should create an account when an individual user is approved', function () {
    $individualUser = IndividualUser::factory()->create([
        'status' => UserStatus::WaitingForApproval,
    ]);

    expect($individualUser->accounts)->toBeEmpty();

    $individualUser->update(['status' => UserStatus::Approved]);

    $individualUser->refresh();

    expect($individualUser->accounts->first())->not->toBeNull()
        ->and($individualUser->accounts->first()->number)->toBeInt()
        ->and($individualUser->accounts->first()->status)->toBe(\App\Enums\AccountStatus::Active);
});
