<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(public readonly string $productName, public readonly int $available)
    {
        parent::__construct("Insufficient stock for \"{$productName}\". Only {$available} left.");
    }
}
