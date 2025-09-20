<?php

use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Laravel\Sanctum\Sanctum;

it('should be able to get user information via sanctum', function (string $model) {
    /** @var IndividualUser|OrganizationUser $user */
    $user = $model::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/user');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'status',
            ],
        ]);
})->with(function () {
    return [
        'Individual User' => [IndividualUser::class],
        'Organization User' => [OrganizationUser::class],
    ];
});
