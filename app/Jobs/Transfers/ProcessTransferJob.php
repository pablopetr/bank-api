<?php

namespace App\Jobs\Transfers;

use App\Actions\Transfers\ProcessTransfer as ProcessTransferAction;
use App\Models\Transfer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTransferJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Transfer $transfer)
    {
    }

    public function handle(): void
    {
        (new ProcessTransferAction())->execute($this->transfer);
    }
}
