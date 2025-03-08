<?php

declare(strict_types=1);

namespace App\Core\Application\Mapper\Wallet;

use App\Core\Domain\Aggregate\WalletModel;
use App\Entity\Wallet;

class WalletMapper implements WalletMapperInterface
{
    public function __construct(
    ) {
    }

    public function fromEntity(Wallet $entity): WalletModel
    {
        return new WalletModel(
            phoneNumber: \App\Core\Domain\ValueObject\WalletPhoneNumber::create($entity->phoneNumber),
            balance: \App\Core\Domain\ValueObject\WalletBalance::create($entity->balance),
            provider: \App\Core\Domain\ValueObject\WalletProvider::create($entity->provider),
            id: \App\Core\Domain\ValueObject\WalletId::create($entity->getId()),
        );
    }

    public function toEntity(WalletModel $model): Wallet
    {
        return new Wallet(
            phoneNumber: $model->phoneNumber?->value(),
            balance: $model->balance?->value(),
            provider: $model->provider?->value(),
        );
    }

    public function fromArray(array $data): WalletModel
    {
        return new WalletModel(
            phoneNumber: \App\Core\Domain\ValueObject\WalletPhoneNumber::create($data['phoneNumber'] ?? null),
            balance: \App\Core\Domain\ValueObject\WalletBalance::create($data['balance'] ?? null),
            provider: \App\Core\Domain\ValueObject\WalletProvider::create($data['provider'] ?? null),
            id: \App\Core\Domain\ValueObject\WalletId::create($data['id'] ?? null),
        );
    }

    public function toArray(WalletModel $model): array
    {
        return [
            'phoneNumber' => $model->phoneNumber?->valueView(),
            'balance' => $model->balance?->valueView(),
            'provider' => $model->provider?->valueView(),
            'id' => $model->id?->valueView(),
        ];
    }
}
