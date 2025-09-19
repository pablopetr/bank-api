<?php

namespace App\Exceptions\Wallets;

use Exception;

class InsufficientBalanceToTransferException extends Exception
{
    protected $message = 'Insufficient balance in the source wallet.';
}
