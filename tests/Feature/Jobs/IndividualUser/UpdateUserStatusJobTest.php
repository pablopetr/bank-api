<?php

use App\Enums\UserStatus;
use App\Jobs\IndividualUser\UpdateUserStatusJob;
use App\Models\IndividualUser;

it('should be able to instantiate the job', function () {
    $user = IndividualUser::factory()->create();

    $job = new UpdateUserstatusJob($user->id, UserStatus::Approved);
    expect($job)->toBeInstanceOf(UpdateUserStatusJob::class);
});

it('should be able to update user status', function () {
    $user = IndividualUser::factory()->create(['status' => UserStatus::WaitingForApproval]);

    $job = new UpdateUserStatusJob($user->id, UserStatus::Approved);
    $job->handle();

    $user->refresh();

    expect($user->status)->toBe(UserStatus::Approved);
});
