<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Story;

use Zenstruck\Foundry\Story;

/**
 * Class WalletStory.
 * Story to create 15 instances of Wallet using the factory.
 */
final class WalletStory extends Story
{
    public function build(): void
    {
        \App\Core\Infrastructure\Factory\WalletFactory::createMany(15);
    }
}
