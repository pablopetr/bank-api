<?php

namespace Database\Factories;

use App\Enums\UserStatus;

/*
* @mixin \Illuminate\Database\Eloquent\Factories\Factory
*/

trait HasUserStatus
{
    public function waitingForApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::WaitingForApproval->value,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::Rejected->value,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::Approved->value,
        ]);
    }
}
