<?php

use App\Actions\Transfers\CreateTransfer;
use App\Actions\Transfers\ProcessTransfer;
use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to process the transfer and mark as approved', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = (new CreateTransfer)->execute($fromWallet, $toWallet, 50);

    (new ProcessTransfer)->execute($transfer);

    $this->assertDatabaseHas(Transfer::class, [
        'id' => $transfer->id,
        'status' => TransferStatus::Completed->value,
    ]);

    $fromWallet->refresh();
    $toWallet->refresh();

    expect($fromWallet->balance)->toBe(number_format(50.00, 2))
        ->and($toWallet->balance)->toBe(number_format(100.00, 2));
});

it('should not process transfer when the transfer is not pending', function (TransferStatus $status) {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = Transfer::create([
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => $status,
    ]);

    (new ProcessTransfer)->execute($transfer);

    $this->assertDatabaseHas(Transfer::class, [
        'id' => $transfer->id,
        'status' => $status->value,
    ]);
})->with([
    TransferStatus::Completed->value => [TransferStatus::Completed],
    TransferStatus::Failed->value => [TransferStatus::Failed],
]);

it('should not process transfer when the from wallet has insufficient balance', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 30, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = (new CreateTransfer)->execute($fromWallet, $toWallet, 50);

    (new ProcessTransfer)->execute($transfer);

    $this->assertDatabaseHas(Transfer::class, [
        'id' => $transfer->id,
        'status' => TransferStatus::Failed->value,
    ]);

    $fromWallet->refresh();
    $toWallet->refresh();

    expect($fromWallet->balance)->toBe(number_format(30.00, 2))
        ->and($toWallet->balance)->toBe(number_format(50.00, 2));
});

it('should not process transfer when the from wallet is inactive', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Inactive, 'name' => 'from wallet']);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'name' => 'to wallet']);

    $transfer = Transfer::create([
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => TransferStatus::Pending,
    ]);

    (new ProcessTransfer)->execute($transfer);

    $this->assertDatabaseHas(Transfer::class, [
        'id' => $transfer->id,
        'status' => TransferStatus::Failed->value,
    ]);

    $fromWallet->refresh();
    $toWallet->refresh();

    expect($fromWallet->balance)->toBe(number_format(100.00, 2))
        ->and($toWallet->balance)->toBe(number_format(50.00, 2));
});
