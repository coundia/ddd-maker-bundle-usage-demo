<?php

namespace App\Core\Application\Query;

class FindByPhoneNumberWalletQuery
{
    public function __construct(
        public ?\App\Core\Domain\ValueObject\WalletPhoneNumber $phoneNumber,
    ) {
    }
}
