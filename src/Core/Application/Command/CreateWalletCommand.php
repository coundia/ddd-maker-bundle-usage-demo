<?php

declare(strict_types=1);

namespace App\Core\Application\Command;

/**
 * Class CreateWalletCommand Represents a command for creating a Wallet.
 */
class CreateWalletCommand
{
    public function __construct(
        public ?\App\Core\Domain\ValueObject\WalletPhoneNumber $phoneNumber,
        public ?\App\Core\Domain\ValueObject\WalletBalance $balance,
        public ?\App\Core\Domain\ValueObject\WalletProvider $provider,
    ) {
    }
}
