<?php

namespace Database\Factories;

use App\Enums\WalletStatus;
use App\Enums\WalletType;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'account_id' => Account::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'type' => $this->faker->randomElement(WalletType::values()),
            'status' => $this->faker->randomElement(WalletStatus::values()),
        ];
    }
}
