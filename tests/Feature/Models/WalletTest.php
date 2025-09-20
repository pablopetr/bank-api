<?php

use App\Enums\WalletStatus;
use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;

it('should be able to create a wallet', function (WalletStatus $status) {
    $account = Account::factory()->create();

    $wallet = Wallet::create([
        'name' => 'My Wallet',
        'balance' => 1000.00,
        'account_id' => $account->id,
        'status' => $status,
    ]);

    $wallet->refresh();

    expect($wallet)->toBeInstanceOf(Wallet::class)
        ->and($wallet->name)->toBe('My Wallet')
        ->and($wallet->balance)->toBe(number_format(1000, 2, '.', ''))
        ->and($wallet->type)->toBe(WalletType::Default)
        ->and($wallet->account_id)->toBe($account->id)
        ->and($wallet->status)->toBe($status);
})->with(function () {
    return collect(WalletStatus::cases())
        ->mapWithKeys(function (WalletStatus $status) {
            return [$status->value => [$status]];
        });
});

it('should not be able to create a wallet with invalid type', function () {
    $account = Account::factory()->create();

    Wallet::create([
        'name' => 'My Wallet',
        'balance' => 1000.00,
        'account_id' => $account->id,
        'type' => 'invalid-type',
    ]);
})->throws(ValueError::class);

it('should not be able to create a wallet with invalid status', function () {
    $account = Account::factory()->create();

    Wallet::create([
        'name' => 'My Wallet',
        'balance' => 1000.00,
        'account_id' => $account->id,
        'type' => WalletType::Wallet,
        'status' => 'invalid-status',
    ]);
})->throws(ValueError::class);

it('should be able to get the account relationship', function () {
    $account = Account::factory()->create();

    $wallet = Wallet::create([
        'name' => 'My Wallet',
        'balance' => 1000.00,
        'account_id' => $account->id,
        'status' => WalletStatus::Active,
    ]);

    expect($wallet->account)->toBeInstanceOf(Account::class)
        ->and($wallet->account->id)->toBe($account->id);
});
