<?php

namespace App\Core\Application\QueryHandler;

use App\Core\Application\Query\FindByIdWalletQuery;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FindByIdWalletQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private \App\Core\Application\Mapper\Wallet\WalletMapper $mapper
    ) {
    }

    public function __invoke(FindByIdWalletQuery $query): array
    {
        $parameter = $query->id?->value();
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e')
        ->from(Wallet::class, 'e')
        ->where('e.id = :parameter')
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
