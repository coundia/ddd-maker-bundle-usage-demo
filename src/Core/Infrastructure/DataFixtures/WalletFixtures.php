<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Infrastructure\Story\WalletStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class WalletFixtures.
 * Seeds the database with initial data using the story.
 */
class WalletFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        WalletStory::load();
    }
}
