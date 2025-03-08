<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence;

use App\Core\Domain\Aggregate\WalletModel;
use App\Core\Domain\Repository\WalletRepositoryInterface;
use App\Core\Domain\ValueObject\WalletId;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class WalletRepository implements WalletRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private \App\Core\Application\Mapper\Wallet\WalletMapperInterface $mapper
    ) {
    }

    public function save(WalletModel $wallet): WalletModel
    {
        $entity = new Wallet(
            phoneNumber: $wallet->phoneNumber?->value(),
            balance: $wallet->balance?->value(),
            provider: $wallet->provider?->value(),
        );

        if (!$this->em->contains($entity)) {
            $this->em->persist($entity);
        }

        $this->em->flush();

        return $this->mapper->fromEntity($entity);
    }

    public function update(WalletModel $wallet, WalletId $id): WalletModel
    {
        $entity = $this->em->find(Wallet::class, $id?->value());

        if ($entity) {
            $this->em->flush();
        }

        return $this->mapper->fromEntity($entity);
    }

    public function find(WalletId $id): ?WalletModel
    {
        $entity = $this->em->find(Wallet::class, $id?->value());

        return $entity ? $this->mapper->fromEntity($entity) : null;
    }

    public function delete(WalletId $id): WalletId
    {
        $entity = $this->em->find(Wallet::class, $id?->value());

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }

        return $id;
    }

    public function findById(WalletId $id): ?WalletModel
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e')
        ->from(Wallet::class, 'e')
        ->where('e.id = :id')
        ->setParameter('id', $id?->value());

        try {
            $entity = $qb->getQuery()->getSingleResult();

            return $this->mapper->fromEntity($entity);
        } catch (NoResultException) {
            return null;
        }
    }

    /**
     * @return array<\App\Core\Domain\Aggregate\WalletModel>|null
     */
    public function findAll(): array
    {
        return array_map(
            fn ($entity) => $this->mapper->fromEntity($entity),
            $this->em->createQueryBuilder()
            ->select('e')
            ->from(Wallet::class, 'e')
            ->getQuery()
            ->getResult()
        );
    }

    /**
     * @return array<\App\Core\Domain\Aggregate\WalletModel>|null
     */
    public function findByCriteria(array $criteria): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e')
        ->from(Wallet::class, 'e');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("e.$field = :$field")
            ->setParameter($field, $value);
        }

        return array_map(
            fn ($entity) => $this->mapper->fromEntity($entity),
            $qb->getQuery()->getResult()
        );
    }

    /**
     * @return array{
     *     items: array<\App\Core\Domain\Aggregate\WalletModel>,
     *     total: int,
     *     page: int,
     *     limit: int
     * }
     */
    public function findPaginated(int $page, int $limit, array $criteria = []): array
    {
        $qb = $this->em->createQueryBuilder()
        ->select('e')
        ->from(Wallet::class, 'e');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("e.$field = :$field")
            ->setParameter($field, $value);
        }

        $total = (clone $qb)
        ->select('COUNT(e.id)')
        ->getQuery()
        ->getSingleScalarResult();

        $items = $qb->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();

        return [
            'items' => array_map(
                fn ($entity) => $this->mapper->toArray($this->mapper->fromEntity($entity)),
                $items
            ),
            'total' => (int) $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }
}
