<?php

namespace App\Exceptions\Wallets;

use Exception;

class DestinationWalletInactiveException extends Exception
{
    protected $message = "The destination wallet is inactive.";
}
