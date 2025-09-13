<?php

namespace Database\Factories;

use App\Enums\AccountStatus;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        $user = $this->faker->randomElement([
            IndividualUser::factory(),
            OrganizationUser::factory(),
        ])->create();

        return [
            'accountable_id' => $user->id,
            'accountable_type' => get_class($user),
            'status' => AccountStatus::Active->value,
        ];
    }
}
