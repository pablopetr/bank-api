<?php

use App\Enums\UserStatus;
use App\Jobs\OrganizationUser\UpdateUserStatusJob;
use App\Models\OrganizationUser;

it('should be able to instantiate the job', function () {
    $user = OrganizationUser::factory()->create();

    $job = new UpdateUserStatusJob($user->id, UserStatus::Approved);

    expect($job)->toBeInstanceOf(UpdateUserStatusJob::class);
});

it('should be able to update the user status', function () {
    $user = OrganizationUser::factory()->create(['status' => UserStatus::WaitingForApproval]);

    $job = new UpdateUserStatusJob($user->id, UserStatus::Approved);
    $job->handle();

    $user->refresh();

    expect($user->status)->toBe(UserStatus::Approved);
});
