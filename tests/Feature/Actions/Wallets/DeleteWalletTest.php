<?php

use App\Actions\Wallets\DeleteWallet;
use App\Enums\WalletType;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to soft delete a wallet', function () {
    $wallet = Wallet::factory()->create(['balance' => 0, 'type' => WalletType::Wallet]);

    $this->assertDatabaseCount(Wallet::class, 1);

    (new DeleteWallet)->execute($wallet->id);

    $this->assertDatabaseCount(Wallet::class, 1);
    $this->assertSoftDeleted($wallet);
});

it('should not be able to delete a wallet with positive balance', function () {
    $wallet = Wallet::factory()->create(['balance' => 0.01]);

    $this->assertDatabaseCount(Wallet::class, 1);

    expect(fn () => (new DeleteWallet)->execute($wallet->id))
        ->toThrow(RuntimeException::class, 'Cannot delete a wallet with a positive balance.');

    $this->assertDatabaseCount(Wallet::class, 1);

    $wallet->refresh();

    $this->assertNull($wallet->deleted_at);
});

it('should not be able to delete a default wallet', function () {
    $wallet = Wallet::factory()->create(['balance' => 0, 'type' => WalletType::Default]);

    $this->assertDatabaseCount(Wallet::class, 1);

    expect(fn () => (new DeleteWallet)->execute($wallet->id))
        ->toThrow(RuntimeException::class, 'Cannot delete the default wallet.');

    $this->assertDatabaseCount(Wallet::class, 1);

    $wallet->refresh();

    $this->assertNull($wallet->deleted_at);
});
