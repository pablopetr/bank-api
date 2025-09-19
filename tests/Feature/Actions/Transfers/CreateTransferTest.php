<?php

use App\Actions\Transfers\CreateTransfer;
use App\Enums\WalletStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create a transfer', function () {
    $walletFrom = Wallet::factory()->create(['balance' => 100]);
    $walletTo = Wallet::factory()->create(['balance' => 50]);

    (new CreateTransfer)->execute($walletFrom, $walletTo, 50);

    $this->assertDatabaseHas(Transfer::class, [
        'from_wallet_id' => $walletFrom->id,
        'to_wallet_id' => $walletTo->id,
        'amount' => 50,
    ]);
});

it('should not be able to create a transfer with a non positive amount', function () {
    $walletFrom = Wallet::factory()->create(['balance' => 100]);
    $walletTo = Wallet::factory()->create(['balance' => 50]);

    expect(fn () => (new CreateTransfer)->execute($walletFrom, $walletTo, 0))
        ->toThrow(RuntimeException::class, 'The transfer amount must be greater than zero.');

    $this->assertDatabaseCount(Transfer::class, 0);
});

it('should not be able to transfer from an inactive wallet', function () {
    $walletFrom = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Inactive]);
    $walletTo = Wallet::factory()->create(['balance' => 50]);

    expect(fn () => (new CreateTransfer)->execute($walletFrom, $walletTo, 0))
        ->toThrow(RuntimeException::class, 'The source wallet is inactive.');

    $this->assertDatabaseCount(Transfer::class, 0);
});

it('should not be able to transfer to an inactive wallet', function () {
    $walletFrom = Wallet::factory()->create(['balance' => 100]);
    $walletTo = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Inactive]);

    expect(fn () => (new CreateTransfer)->execute($walletFrom, $walletTo, 0))
        ->toThrow(RuntimeException::class, 'The destination wallet is inactive.');

    $this->assertDatabaseCount(Transfer::class, 0);
});

it('should not be able to transfer to the same wallet', function () {
    $walletFrom = Wallet::factory()->create(['balance' => 100]);

    expect(fn () => (new CreateTransfer)->execute($walletFrom, $walletFrom, 0))
        ->toThrow(RuntimeException::class, 'Cannot transfer to the same wallet.');

    $this->assertDatabaseCount(Transfer::class, 0);
});
