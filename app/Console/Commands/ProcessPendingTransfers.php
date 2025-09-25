<?php

namespace App\Console\Commands;

use App\Enums\TransferStatus;
use App\Jobs\Transfers\ProcessTransferJob;
use App\Models\Transfer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ProcessPendingTransfers extends Command
{
    protected $signature = 'app:process-pending-transfers';
    protected $description = 'Command description';

    public function handle(): int
    {
        Transfer::query()
            ->where('status', TransferStatus::Pending->value)
            ->limit(100)
            ->orderBy('created_at', 'asc')
            ->get()
            ->each(function (Transfer $transfer) {
                ProcessTransferJob::dispatch($transfer);

                $this->info("Dispatched job for Transfer ID: {$transfer->id}");
            });

        return CommandAlias::SUCCESS;
    }
}
