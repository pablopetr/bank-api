<?php

namespace Database\Seeders;

use App\Models\IndividualUser;
use Illuminate\Database\Seeder;

class IndividualUserSeeder extends Seeder
{
    public function run(): void
    {
        IndividualUser::factory()->count(10)->create();
    }
}
