<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson as Json;

uses(RefreshDatabase::class);

it('should be able to register a organization user', function () {
    $this->postJson(route('organization-user.register'), [
        'name' => 'User Test',
        'email' => 'user-test@user.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertStatus(201)
        ->assertJson(fn (Json $json) => $json->where('token_type', 'Bearer')
            ->has('access_token')
            ->etc()
        );
});

it('should return validation errors', function ($field, $value, $error) {
    $valid = [
        'name' => 'John',
        'email' => 'john@example.com',
        'password' => 'Secret123!',
        'password_confirmation' => 'Secret123!',
    ];
    $valid[$field] = $value;

    $errorKey = $field === 'password_confirmation' ? 'password' : $field;

    $this->postJson(route('organization-user.register'), $valid)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$errorKey])
        ->assertJson(
            fn (Json $json) => $json->where("errors.{$errorKey}.0", $error)->etc()
        );
})->with([
    'Name is required' => ['name', '', 'The name field is required.'],
    'Name max length' => ['name', str_repeat('a', 256), 'The name field must not be greater than 255 characters.'],

    'Email is required' => ['email', '', 'The email field is required.'],
    'Email must be valid' => ['email', 'invalid-email', 'The email field must be a valid email address.'],
    'Email max length' => ['email', str_repeat('a', 256).'@example.com', 'The email field must not be greater than 255 characters.'],

    'Password is required' => ['password', '', 'The password field is required.'],
    'Password min length' => ['password', 'short', 'The password field must be at least 8 characters.'],
    'Password max length' => ['password', str_repeat('a', 256), 'The password field must not be greater than 255 characters.'],

    'Password match' => ['password_confirmation', 'different', 'The password field confirmation does not match.'],
]);
