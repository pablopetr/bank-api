<?php

namespace App\Exceptions\Wallets;

use Exception;

class SameWalletTransferNotAllowed extends Exception
{
    protected $message = 'Cannot transfer to the same wallet.';
}
