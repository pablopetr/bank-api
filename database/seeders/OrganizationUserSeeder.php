<?php

namespace Database\Seeders;

use App\Events\UserCreated;
use App\Models\OrganizationUser;
use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = OrganizationUser::factory()->count(10)->create();

        foreach ($users as $user) {
            event(new UserCreated($user));
        }
    }
}
