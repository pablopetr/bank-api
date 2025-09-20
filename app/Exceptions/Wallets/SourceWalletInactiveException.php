<?php

namespace App\Exceptions\Wallets;

use Exception;

class SourceWalletInactiveException extends Exception
{
    protected $message = 'The source wallet is inactive.';
}
