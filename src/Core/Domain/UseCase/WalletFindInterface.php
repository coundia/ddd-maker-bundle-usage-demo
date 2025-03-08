<?php

declare(strict_types=1);

namespace App\Core\Domain\UseCase;

/**
 * Interface WalletFindInterface* Defines the contract for querying Wallet entities.
 */
interface WalletFindInterface
{
    public function find(\App\Core\Domain\ValueObject\WalletId $id): ?\App\Core\Domain\Aggregate\WalletModel;

    public function findAll(): array;

    public function findPaginated(int $page, int $limit, array $criteria = []): array;
}
