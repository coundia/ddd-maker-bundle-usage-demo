<?php

declare(strict_types=1);

namespace App\Core\Domain\UseCase;

/**
 * Interface WalletUpdateInterface* Defines the contract for updating Wallet entities.
 */
interface WalletUpdateInterface
{
    public function update(\App\Core\Domain\Aggregate\WalletModel $entity, \App\Core\Domain\ValueObject\WalletId $entityId): \App\Core\Domain\Aggregate\WalletModel;
}
