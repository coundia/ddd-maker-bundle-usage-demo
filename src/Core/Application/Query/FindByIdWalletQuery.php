<?php

namespace App\Core\Application\Query;

class FindByIdWalletQuery
{
    public function __construct(
        public ?\App\Core\Domain\ValueObject\WalletId $id,
    ) {
    }
}
