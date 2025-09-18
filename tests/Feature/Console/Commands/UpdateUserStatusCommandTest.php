<?php

use App\Enums\UserStatus;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use \App\Jobs\OrganizationUser\UpdateUsersStatusJob as OrganizationUserUpdateUsersStatusJob;
use \App\Jobs\IndividualUser\UpdateUsersStatusJob as IndividualUserUpdateUsersStatusJob;

uses(RefreshDatabase::class);

it('dispatches a batch with jobs to update users', function (string $modelClass, string $jobClass) {
    Bus::fake();

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
