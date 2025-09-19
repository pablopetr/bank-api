<?php

namespace App\Exceptions\Wallets;

use Exception;

class InsufficientBalanceInWalletToTransferException extends Exception
{
    protected $message = 'Insufficient balance in the source wallet.';
}
