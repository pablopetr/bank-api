<?php

namespace App\Exceptions\Wallets;

use Exception;

class InvalidTransferStatusException extends Exception
{
    protected $message = 'Only pending transfers can be validated.';
}
