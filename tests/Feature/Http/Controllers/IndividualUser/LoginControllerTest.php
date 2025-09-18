<?php

use App\Models\IndividualUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('should be able to login with an individual user', function () {
    $individualUser = IndividualUser::factory()->approved()->create();

    $this->postJson(route('individual-user.login'), [
        'email' => $individualUser->email,
        'password' => 'password',
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
});

it('should not be able to login with invalid credentials', function () {
    $individualUser = IndividualUser::factory()->approved()->create();

    $this->postJson(route('individual-user.login'), [
        'email' => $individualUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});

it('should not be able to login when the user is waiting for approval', function () {
    $individualUser = IndividualUser::factory()->waitingForApproval()->create();

    $this->postJson(route('individual-user.login'), [
        'email' => $individualUser->email,
        'password' => 'password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'User is not active.',
        ]);
});

it('should not be able to login when the user is rejected', function () {
    $individualUser = IndividualUser::factory()->rejected()->create();

    $this->postJson(route('individual-user.login'), [
        'email' => $individualUser->email,
        'password' => 'password',
    ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'User is not active.',
        ]);
});
