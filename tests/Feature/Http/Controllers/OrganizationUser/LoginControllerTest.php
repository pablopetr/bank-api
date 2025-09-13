<?php

use App\Models\OrganizationUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to login an organization user', function () {
    $organizationUser = OrganizationUser::factory()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'password',
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
});

it('should not be able to login with invalid credentials', function () {
    $organizationUser = OrganizationUser::factory()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});
