<?php

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create an account', function () {
    Carbon::setTestNow(now());

    $account = Account::create([
        'number' => Account::INITIAL_NUMBER,
        'status' => AccountStatus::Active,
        'accountable_id' => 1,
        'accountable_type' => 'App\Models\OrganizationUser',
    ]);

    expect($account)->toBeInstanceOf(Account::class)
        ->and($account)
        ->toBeInstanceOf(Account::class)
        ->and($account->exists)->toBeTrue()
        ->and($account->wasRecentlyCreated)->toBeTrue()
        ->and($account->number)->toBe(Account::INITIAL_NUMBER)
        ->and($account->status)->toBe(AccountStatus::Active)
        ->and($account->accountable_id)->toBe(1)
        ->and($account->accountable_type)->toBe('App\Models\OrganizationUser')
        ->and($account->created_at->toDateTimeString())->toBe(now()->toDateTimeString())
        ->and($account->updated_at->toDateTimeString())->toBe(now()->toDateTimeString());
})->with(function () {
    return collect(AccountStatus::cases())
        ->mapWithKeys(function (AccountStatus $status) {
            return [$status->value => [$status]];
        });
});

it('should not be able to create an account with invalid status', function () {
    Account::create([
        'number' => Account::INITIAL_NUMBER,
        'status' => 'invalid-status',
        'accountable_id' => 1,
        'accountable_type' => 'App\Models\OrganizationUser',
    ]);
})->throws(ValueError::class);

it('should be able to get the accountable relationship', function (string $morphableClass) {
    $morphable = $morphableClass::factory()->create();

    $account = Account::create([
        'number' => Account::INITIAL_NUMBER,
        'status' => AccountStatus::Active,
        'accountable_id' => $morphable->id,
        'accountable_type' => $morphableClass,
    ]);

    expect($account)->toBeInstanceOf(Account::class)
        ->and($account->accountable)->toBeInstanceOf($morphableClass)
        ->and($account->accountable->id)->toBe($morphable->id);
})->with([
    OrganizationUser::class => [OrganizationUser::class],
    IndividualUser::class => [IndividualUser::class],
]);

it('should be able to get wallets relationship', function () {
    $account = Account::factory()->create();

    $wallets = Wallet::factory()->count(3)->create([
        'account_id' => $account->id,
    ]);

    expect($account->wallets)->toBeInstanceOf(Collection::class)
        ->and($account->wallets->count())->toBe(3)
        ->and($account->wallets->first())->toBeInstanceOf(Wallet::class)
        ->and($account->wallets->pluck('id')->toArray())->toEqual($wallets->pluck('id')->toArray());
});
