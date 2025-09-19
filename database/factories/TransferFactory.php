<?php

namespace Database\Factories;

use App\Enums\TransferStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transfer>
 */
class TransferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(TransferStatus::values()),
            'from_wallet_id' => Wallet::factory(),
            'to_wallet_id' => Wallet::factory(),
        ];
    }
}
