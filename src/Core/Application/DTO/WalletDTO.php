<?php

declare(strict_types=1);

namespace App\Core\Application\DTO;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class WalletDTO* abstract Transfer Object for Wallet.
 */
abstract class WalletDTO
{
    public function __construct(
        #[Groups(['default'])]
        public ?string $phoneNumber,
        #[Groups(['default'])]
        public ?float $balance,
        #[Groups(['default'])]
        public ?string $provider,
        #[Groups(['default'])]
        public ?string $id,
    ) {
    }
}
