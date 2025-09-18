<?php

use App\Actions\Wallets\CreateCustomWallet;
use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to create custom wallet', function () {
   $this->assertDatabaseCount(Wallet::class, 0);

   $account = Account::factory()->create();

    (new CreateCustomWallet())->execute($account, $walletName = 'My Custom Wallet');

    $this->assertDatabaseCount(Wallet::class, 1);

    $this->assertDatabaseHas(Wallet::class, [
        'name' => $walletName,
        'account_id' => $account->id,
    ]);
});
