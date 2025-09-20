<?php

use App\Enums\TransferStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create a transfer', function (TransferStatus $transferStatus) {
    $fromWallet = Wallet::factory()->create();
    $toWallet = Wallet::factory()->create();

    $transfer = Transfer::create([
        'amount' => 100.00,
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'status' => $transferStatus,
    ]);

    expect($transfer)->toBeInstanceOf(Transfer::class)
        ->and($transfer->amount)->toBe(100.00)
        ->and($transfer->from_wallet_id)->toBe($fromWallet->id)
        ->and($transfer->to_wallet_id)->toBe($toWallet->id)
        ->and($transfer->status)->toBe($transferStatus);
})->with(function () {
    return collect(TransferStatus::cases())
        ->mapWithKeys(function (TransferStatus $status) {
            return [$status->value => [$status]];
        });
});

it('should not be able to create a transfer with invalid status', function () {
    $fromWallet = Wallet::factory()->create();
    $toWallet = Wallet::factory()->create();

    Transfer::create([
        'amount' => 100.00,
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'status' => 'invalid-status',
    ]);
})->throws(ValueError::class);

it('should be able to get from and to wallet relationships', function () {
    $fromWallet = Wallet::factory()->create();
    $toWallet = Wallet::factory()->create();

    $transfer = Transfer::create([
        'amount' => 100.00,
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'status' => TransferStatus::Pending,
    ]);

    expect($transfer->fromWallet)->toBeInstanceOf(Wallet::class)
        ->and($transfer->fromWallet->id)->toBe($fromWallet->id)
        ->and($transfer->toWallet)->toBeInstanceOf(Wallet::class)
        ->and($transfer->toWallet->id)->toBe($toWallet->id);
});
