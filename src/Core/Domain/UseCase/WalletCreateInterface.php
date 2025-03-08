<?php

declare(strict_types=1);

namespace App\Core\Domain\UseCase;

/**
 * Interface WalletCreateInterface* Defines the contract for creating a Wallet.
 */
interface WalletCreateInterface
{
    public function create(\App\Core\Domain\Aggregate\WalletModel $wallet): \App\Core\Domain\Aggregate\WalletModel;
}
