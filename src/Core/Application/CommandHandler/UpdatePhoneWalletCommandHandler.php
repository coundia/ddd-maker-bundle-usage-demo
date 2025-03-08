<?php
/**
 * Class UpdatePhoneWalletCommandHandler*.
 *
 * @see \App\Core\Application\Command\UpdatePhoneWalletCommand*
 */
declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdatePhoneWalletCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private \App\Core\Application\Mapper\Wallet\WalletMapper $mapper
    ) {
    }

    public function __invoke(\App\Core\Application\Command\UpdatePhoneWalletCommand $command): \App\Core\Domain\Aggregate\WalletModel
    {
        $entity = new Wallet(
            phoneNumber: $command->phoneNumber?->value(),
            balance: $command->balance?->value(),
            provider: $command->provider?->value(),
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->mapper->fromEntity($entity);
    }
}
