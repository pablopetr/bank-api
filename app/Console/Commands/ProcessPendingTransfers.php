<?php

namespace App\Console\Commands;

use App\Actions\Transfers\ProcessTransfer as ProcessTransferAction;
use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ProcessPendingTransfers extends Command
{
    protected $signature = 'app:process-pending-transfers';
    protected $description = 'Command description';

    public function handle(): int
    {
        $fromWallet = Wallet::factory()->create([
            'balance' => 1000,
            'status' => WalletStatus::Active,
        ]);

        $toWallet = Wallet::factory()->create([
            'balance' => 500,
            'status' => WalletStatus::Active,
        ]);

        $transfer = Transfer::factory()->create([
            'status' => TransferStatus::Pending->value,
            'from_wallet_id' => $fromWallet->id,
            'to_wallet_id' => $toWallet->id,
            'amount' => 250,
        ]);

        (new ProcessTransferAction())->execute($transfer);


//        Transfer::query()
//            ->where('status', TransferStatus::Pending->value)
//            ->limit(100)
//            ->orderBy('created_at', 'asc')
//            ->get()
//            ->each(function (Transfer $transfer) {
//                ProcessTransferJob::dispatch($transfer);
//
//                $this->info("Dispatched job for Transfer ID: {$transfer->id}");
//            });
//
        return CommandAlias::SUCCESS;
    }
}
