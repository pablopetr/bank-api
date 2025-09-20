<?php

use App\Actions\Wallets\CreateCustomWallet;
use App\Models\Account;
use App\Models\Wallet;

it('should be able to create custom wallet', function () {
    $this->assertDatabaseCount(Wallet::class, 0);

    $account = Account::factory()->create();

    $wallet = (new CreateCustomWallet)->execute($account, $walletName = 'My Custom Wallet');

    $this->assertInstanceOf(Wallet::class, $wallet);

    $this->assertDatabaseCount(Wallet::class, 1);

    $this->assertDatabaseHas(Wallet::class, [
        'name' => $walletName,
        'account_id' => $account->id,
    ]);
});

it('should throw a runtime error when a wallet with same name exists in the account', function () {
    $account = Account::factory()->create();

    Wallet::factory()->create(['account_id' => $account->id, 'name' => $walletName = 'My Custom Wallet']);

    $this->assertDatabaseCount(Wallet::class, 1);

    $action = (new CreateCustomWallet);

    expect(fn () => $action->execute($account, $walletName))
        ->toThrow(RuntimeException::class, "A wallet with name '{$walletName}' already exists in the account.");

    $this->assertDatabaseCount(Wallet::class, 1);
});
