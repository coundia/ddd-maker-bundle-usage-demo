<?php

declare(strict_types=1);

namespace App\Core\Application\Mapper\Wallet;

use App\Core\Domain\Aggregate\WalletModel;
use App\Entity\Wallet;

interface WalletMapperInterface
{
    public function fromEntity(Wallet $entity): WalletModel;

    public function toEntity(WalletModel $model): Wallet;

    public function fromArray(array $data): WalletModel;

    public function toArray(WalletModel $model): array;
}
