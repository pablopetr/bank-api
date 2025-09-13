<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to login with an individual user', function () {
   $individualUser = \App\Models\IndividualUser::factory()->create();

   $this->postJson(route('individual-user.login'), [
       'email' => $individualUser->email,
       'password' => 'password',
   ])->assertStatus(200)->assertJsonStructure([
       'access_token',
       'token_type',
   ]);
});

it('should not be able to login with invalid credentials', function () {
    $individualUser = \App\Models\IndividualUser::factory()->create();

    $this->postJson(route('individual-user.login'), [
        'email' => $individualUser->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid credentials.',
        ]);
});
