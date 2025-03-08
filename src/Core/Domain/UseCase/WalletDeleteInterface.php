<?php

declare(strict_types=1);

namespace App\Core\Domain\UseCase;

/**
 * Interface WalletDeleteInterface* Defines the contract for deleting a Wallet.
 */
interface WalletDeleteInterface
{
    public function delete(\App\Core\Domain\ValueObject\WalletId $id): \App\Core\Domain\ValueObject\WalletId;
}
