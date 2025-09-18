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

    (new CreateDefaultWallet())->execute($account);

    $this->assertDatabaseCount(Wallet::class, 1);

    $this->assertDatabaseHas(Wallet::class, [
        'account_id' => $account->id,
        'name' => Wallet::DEFAULT_WALLET_NAME,
        'balance' => 0,
        'type' => WalletType::Default->value,
    ]);
});
