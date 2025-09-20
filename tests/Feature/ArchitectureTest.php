<?php

describe('architecture tests', function () {
    arch()
        ->expect('App\Models')
        ->toUseStrictTypes()
        ->not->toUse(['die', 'dd', 'dump']);

    arch()
        ->expect('App\Models')
        ->toBeClasses()
        ->toExtend('Illuminate\Database\Eloquent\Model');
});
