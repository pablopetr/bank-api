<?php

use App\Actions\Wallets\UpdateWallet;
use App\Enums\WalletType;
use App\Models\Wallet;

it('should be able to update wallet', function () {
    $wallet = Wallet::factory()->create(['type' => WalletType::Wallet]);

    $wallet = (new UpdateWallet)->execute($wallet, $newName = 'Updated Wallet Name');

    $this->assertInstanceOf(Wallet::class, $wallet);

    $this->assertDatabaseHas('wallets', [
        'id' => $wallet->id,
        'type' => WalletType::Wallet->value,
        'name' => $newName,
    ]);
});

it('should not be able to update default wallet', function () {
    $wallet = Wallet::factory()->create(['type' => WalletType::Default]);

    $newName = 'Updated Wallet Name';
    expect(fn () => (new UpdateWallet)->execute($wallet, $newName))
        ->toThrow(RuntimeException::class, 'You cannot update the default wallet.');

    $this->assertDatabaseHas('wallets', [
        'id' => $wallet->id,
        'type' => WalletType::Default->value,
        'name' => $wallet->name,
    ]);

    $this->assertDatabaseMissing('wallets', [
        'id' => $wallet->id,
        'name' => $newName,
    ]);
});
