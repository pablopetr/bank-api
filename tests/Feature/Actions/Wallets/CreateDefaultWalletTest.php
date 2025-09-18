<?php

use App\Actions\Wallets\CreateDefaultWallet;
use App\Enums\WalletType;
use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create default account', function () {
    $this->assertDatabaseCount(Wallet::class, 0);

    $account = Account::factory()->create();

    $wallet = (new CreateDefaultWallet())->execute($account);

    $this->assertInstanceOf(Wallet::class, $wallet);

    $this->assertDatabaseHas(Wallet::class, [
        'account_id' => $account->id,
        'name' => Wallet::DEFAULT_WALLET_NAME,
        'balance' => 0,
        'type' => WalletType::Default->value,
    ]);
});
