<?php

declare(strict_types=1);

namespace App\Core\Application\Query;

/**
 * Class FindWalletPaginatedQuery* Query for fetching paginated Wallet records with optional filters.
 */
class FindWalletPaginatedQuery
{
    public function __construct(
        public int $page = 1,
        public int $limit = 10,
        public array $filters = []
    ) {
    }
}
