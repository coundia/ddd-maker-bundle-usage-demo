<?php

namespace App\Core\Application\QueryHandler;

use App\Core\Application\Query\FindByPhoneNumberWalletQuery;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FindByPhoneNumberWalletQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private \App\Core\Application\Mapper\Wallet\WalletMapper $mapper
    ) {
    }

    public function __invoke(FindByPhoneNumberWalletQuery $query): array
    {
        $parameter = $query->phoneNumber?->value();
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e')
        ->from(Wallet::class, 'e')
        ->where('e.phoneNumber = :parameter')
        ->setParameter('parameter', $parameter);

        $result = $qb->getQuery()->getResult();

        if (!$result) {
            throw new \Exception('Not found');
        }

        $data = array_map(
            fn ($entity) => $this->mapper->toArray($this->mapper->fromEntity($entity)),
            $result
        );

        return $data;
    }
}
