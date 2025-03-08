<?php

declare(strict_types=1);

namespace App\Core\Domain\Aggregate;

use App\Core\Domain\ValueObject\WalletBalance;
use App\Core\Domain\ValueObject\WalletId;
use App\Core\Domain\ValueObject\WalletPhoneNumber;
use App\Core\Domain\ValueObject\WalletProvider;
use App\Shared\Domain\Aggregate\AggregateRoot;

/**
 * Class Wallet* Aggregate Root of the Wallet context.
 */
class WalletModel extends AggregateRoot
{
    public function __construct(
        public ?WalletPhoneNumber $phoneNumber,
        public ?WalletBalance $balance,
        public ?WalletProvider $provider,
        public ?WalletId $id,
    ) {
    }

    public static function create(
        ?WalletPhoneNumber $phoneNumber,
        ?WalletBalance $balance,
        ?WalletProvider $provider,
        ?WalletId $id,
    ): self {
        return new self(
            $phoneNumber,
            $balance,
            $provider,
            $id,
        );
    }

    public function update(
        ?WalletPhoneNumber $phoneNumber,
        ?WalletBalance $balance,
        ?WalletProvider $provider,
        ?WalletId $id,
    ): self {
        return new self(
            $phoneNumber,
            $balance,
            $provider,
            $id,
        );
    }
}
