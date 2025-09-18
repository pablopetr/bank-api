<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Jobs\IndividualUser\UpdateUsersStatusJob as ApproveIndividualUsersJob;
use App\Jobs\OrganizationUser\UpdateUsersStatusJob as ApproveOrganizationUsersJob;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

use function Laravel\Prompts\suggest;

class UpdateUsersStatusCommand extends Command
{
    protected $signature = 'app:update-users-status {--status=}';

    protected $description = 'Command description';

    protected array $jobs = [];

    public function handle(): void
    {
        $statusInput = $this->option('status');

        if (! $statusInput) {
            $statusInput = suggest(
                label: 'Insert the status to process all users waiting for approval (Approved or Rejected):',
                options: [UserStatus::Approved->value, UserStatus::Rejected->value],
                placeholder: 'Approved',
                required: true,
                validate: ['status' => 'required|in:'.UserStatus::Approved->value.','.UserStatus::Rejected->value],
            );
        }

        $status = UserStatus::from($statusInput);

        $this->processIndividualUsers($status);
        $this->processOrganizationUsers($status);

        if (empty($this->jobs)) {
            $this->info('No users to review.');

            return;
        }

        Bus::batch($this->jobs)->dispatch();
    }

    private function processIndividualUsers(UserStatus $status): void
    {
        IndividualUser::query()
            ->where('status', UserStatus::WaitingForApproval->value)
            ->chunk(100, function ($users) use ($status) {
                $this->jobs[] = new ApproveIndividualUsersJob($users->pluck('id')->toArray(), $status);
            });
    }

    private function processOrganizationUsers(UserStatus $status): void
    {
        OrganizationUser::query()
            ->where('status', UserStatus::WaitingForApproval->value)
            ->chunk(100, function ($users) use ($status) {
                $this->jobs[] = new ApproveOrganizationUsersJob($users->pluck('id')->toArray(), $status);
            });
    }
}
