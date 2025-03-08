<?php

declare(strict_types=1);

namespace App\Core\Application\Command;

/**
 * Class UpdatePhoneWalletCommand Represents a command for creating a Wallet.
 */
class UpdatePhoneWalletCommand
{
    public function __construct(
        public ?\App\Core\Domain\ValueObject\WalletPhoneNumber $phoneNumber,
        public ?\App\Core\Domain\ValueObject\WalletBalance $balance,
        public ?\App\Core\Domain\ValueObject\WalletProvider $provider,
    ) {
    }
}
