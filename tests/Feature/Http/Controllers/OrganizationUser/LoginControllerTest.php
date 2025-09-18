<?php

use App\Models\OrganizationUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('should be able to login an organization user', function () {
    $organizationUser = OrganizationUser::factory()->approved()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'password',
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
});

it('should not be able to login with invalid credentials', function () {
    $organizationUser = OrganizationUser::factory()->approved()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});

it('should not be able to login when it is waiting for approval', function () {
    $organizationUser = OrganizationUser::factory()->waitingForApproval()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});

it('should not be able to login when it is rejected', function () {
    $organizationUser = OrganizationUser::factory()->rejected()->create();

    $this->postJson(route('organization-user.login'), [
        'email' => $organizationUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});
