<?php

use App\Enums\AccountStatus;
use App\Enums\WalletStatus;
use App\Enums\WalletType;
use App\Models\Account;
use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use App\Models\Transfer;
use App\Models\Wallet;
use Laravel\Sanctum\Sanctum;

it('should be able to create a transfer', function (string $model) {
    /** @var IndividualUser|OrganizationUser $user */
    $user = $model::factory()->create();

    $sourceAccount = Account::factory()->create([
        'accountable_id' => $user->id,
        'accountable_type' => $model,
        'status' => AccountStatus::Active,
    ]);

    Sanctum::actingAs($user, ['*']);

    $sourceWallet = Wallet::factory()->create([
        'account_id' => $sourceAccount->id,
        'balance' => 1000,
        'status' => WalletStatus::Active->value,
    ]);

    $destinationAccount = Account::factory()->create([
        'status' => AccountStatus::Active,
    ]);

    Wallet::factory()->create([
        'account_id' => $destinationAccount->id,
        'balance' => 0,
        'type' => WalletType::Default->value,
        'status' => WalletStatus::Active->value,
    ]);

    $this->postJson(route('transfers.store'), [
        'source_wallet_id' => $sourceWallet->id,
        'destination_account_number' => $destinationAccount->number,
        'amount' => 500,
    ])->assertStatus(201);

    $this->assertDatabaseHas(Transfer::class, [
        'from_wallet_id' => $sourceWallet->id,
        'to_wallet_id' => $destinationAccount->wallets()->first()->id,
        'amount' => 500,
    ]);
})->with([
    'Organization User' => [OrganizationUser::class],
    'Individual User' => [IndividualUser::class],
]);
