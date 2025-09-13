<?php

namespace Database\Seeders;

use App\Models\OrganizationUser;
use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationUser::factory()->count(10)->create();
    }
}
