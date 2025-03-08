<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

/** Exception for Wallet domain errors */
class WalletException extends \Exception
{
    public function __construct(string $message = 'An error occurred in Wallet domain')
    {
        parent::__construct($message);
    }

    public static function because(string $reason): self
    {
        return new self($reason);
    }
}
