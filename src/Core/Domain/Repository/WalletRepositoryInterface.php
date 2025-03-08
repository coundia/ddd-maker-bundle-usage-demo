<?php

declare(strict_types=1);

namespace App\Core\Domain\Repository;

use App\Core\Domain\Aggregate\WalletModel;
use App\Core\Domain\ValueObject\WalletId;

interface WalletRepositoryInterface
{
    public function save(WalletModel $wallet): WalletModel;

    public function update(WalletModel $wallet, WalletId $id): WalletModel;

    public function delete(WalletId $wallet): WalletId;

    public function findById(WalletId $id): ?WalletModel;

    /**
     * @return array<\App\Core\Domain\Aggregate\WalletModel>
     */
    public function findAll(): array;

    /**
     * @return array<\App\Core\Domain\Aggregate\WalletModel>
     */
    public function findByCriteria(array $criteria): array;

    /**
     * @return array{
     *     items: array<\App\Core\Domain\Aggregate\WalletModel>,
     *     total: int,
     *     page: int,
     *     limit: int
     * }
     */
    public function findPaginated(int $page, int $limit, array $criteria = []): array;
}
