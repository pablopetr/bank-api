<?php

namespace Database\Seeders;

use App\Events\UserCreated;
use App\Models\IndividualUser;
use Illuminate\Database\Seeder;

class IndividualUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = IndividualUser::factory()->count(10)->create();

        foreach ($users as $user) {
            event(new UserCreated($user));
        }
    }
}
