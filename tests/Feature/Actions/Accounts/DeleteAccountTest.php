<?php

use App\Actions\Accounts\DeleteAccount;
use App\Models\Account;

it('should be able to soft delete an account', function () {
    $account = Account::factory()->create();

    $this->assertDatabaseCount(Account::class, 1);

    (new DeleteAccount)->execute($account->id);

    $this->assertDatabaseCount(Account::class, 1);
    $this->assertSoftDeleted($account);
});

it('should not be able to delete account with wallets with positive balance', function () {
    $account = Account::factory()->create();
    $account->wallets()->create(['name' => 'Wallet 1', 'balance' => 0.01]);

    $this->assertDatabaseCount(Account::class, 1);

    expect(fn () => (new DeleteAccount)->execute($account->id))
        ->toThrow(RuntimeException::class, 'Cannot delete an account with a positive balance in its wallets.');

    $this->assertDatabaseCount(Account::class, 1);

    $account->refresh();

    $this->assertNull($account->deleted_at);
});
