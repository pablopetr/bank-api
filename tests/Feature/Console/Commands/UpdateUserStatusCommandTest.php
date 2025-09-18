<?php

use App\Enums\UserStatus;
use App\Jobs\IndividualUser\UpdateUsersStatusJob as IndividualUserUpdateUsersStatusJob;
use App\Jobs\OrganizationUser\UpdateUsersStatusJob as OrganizationUserUpdateUsersStatusJob;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

uses(RefreshDatabase::class);

it('dispatches a batch with jobs to update users', function (string $modelClass, string $jobClass) {
    Bus::fake();

    /** @var $modelClass IndividualUser|OrganizationUser */
    $modelClass::factory()->count(1)->create([
        'status' => UserStatus::WaitingForApproval->value,
    ]);

    $this->artisan('app:update-users-status --status=Approved')->assertOk();

    Bus::assertBatched(function (PendingBatch $batch) use ($jobClass) {
        return count($batch->jobs) === 1
            && collect($batch->jobs)->every(fn ($job) => $job instanceof $jobClass);
    });
})->with(function () {
    return [
        'Individual Users' => [
            IndividualUser::class,
            IndividualUserUpdateUsersStatusJob::class,
        ],
        'Organization Users' => [
            OrganizationUser::class,
            OrganizationUserUpdateUsersStatusJob::class,
        ],
    ];
});

it('it should not dispatch job when users was already reviewed', function (string $modelClass, string $jobClass) {
    Bus::fake();

    /** @var $modelClass IndividualUser|OrganizationUser */
    $modelClass::factory()->count(1)->create([
        'status' => UserStatus::Rejected->value,
    ]);

    $this->artisan('app:update-users-status --status=Approved')
        ->assertOk()
        ->expectsOutput('No users to review.');

    Bus::assertBatchCount(0);
})->with(function () {
    return [
        'Individual Users' => [
            IndividualUser::class,
            IndividualUserUpdateUsersStatusJob::class,
        ],
        'Organization Users' => [
            OrganizationUser::class,
            OrganizationUserUpdateUsersStatusJob::class,
        ],
    ];
});
