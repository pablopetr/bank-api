<?php

use App\Actions\Transfers\CreateTransfer;
use App\Actions\Transfers\ValidateTransfer;
use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Exceptions\Wallets\DestinationWalletInactiveException;
use App\Exceptions\Wallets\InsufficientBalanceInWalletToTransferException;
use App\Exceptions\Wallets\InvalidTransferStatusException;
use App\Exceptions\Wallets\SourceWalletInactiveException;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to validate a transfer', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = (new CreateTransfer)->execute($fromWallet, $toWallet, 50);

    $this->assertDatabaseHas(Transfer::class, [
        'id' => $transfer->id,
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => TransferStatus::Pending->value,
    ]);

    expect((new ValidateTransfer)->execute($transfer))->toBeTrue();
});

it('should not validate a transfer when the transfer is not pending', function (TransferStatus $status) {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = Transfer::create([
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => $status,
    ]);

    expect(fn () => (new ValidateTransfer)->execute($transfer))
        ->toThrow(InvalidTransferStatusException::class, 'Only pending transfers can be validated.');
})->with([
    TransferStatus::Completed->value => [TransferStatus::Completed],
    TransferStatus::Failed->value => [TransferStatus::Failed],
]);

it('should not validate a transfer when the from wallet has insufficient balance', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 30, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Active]);

    $transfer = (new CreateTransfer)->execute($fromWallet, $toWallet, 50);

    expect(fn () => (new ValidateTransfer)->execute($transfer))
        ->toThrow(InsufficientBalanceInWalletToTransferException::class, 'Insufficient balance in the source wallet.');
});

it('should not validate a transfer when the from wallet is inactive', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Inactive, 'name' => 'from wallet']);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'name' => 'to wallet']);

    $transfer = Transfer::create([
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => TransferStatus::Pending,
    ]);

    expect(fn () => (new ValidateTransfer)->execute($transfer))
        ->toThrow(SourceWalletInactiveException::class, 'The source wallet is inactive.');
});

it('should not validate a transfer when the to wallet is inactive', function () {
    $fromWallet = Wallet::factory()->create(['balance' => 100, 'status' => WalletStatus::Active]);
    $toWallet = Wallet::factory()->create(['balance' => 50, 'status' => WalletStatus::Inactive]);

    $transfer = Transfer::factory()->create([
        'from_wallet_id' => $fromWallet->id,
        'to_wallet_id' => $toWallet->id,
        'amount' => 50,
        'status' => TransferStatus::Pending,
    ]);

    expect(fn () => (new ValidateTransfer)->execute($transfer))
        ->toThrow(DestinationWalletInactiveException::class, 'The destination wallet is inactive.');
});
