<?php

declare(strict_types=1);

namespace App\Tests\Functional\Wallet;

use App\Core\Infrastructure\Factory\WalletFactory;
use App\Tests\Shared\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

use function Zenstruck\Foundry\faker;

class FindWalletPaginatedQueryControllerTest extends BaseWebTestCase
{
    use Factories;

    public function testListEntitiesWithPagination(): void
    {
        $entity = WalletFactory::createOne([
            'phoneNumber' => $valuePhoneNumber = faker()->sentence(),
            'balance' => $valueBalance = faker()->randomFloat(2, 0, 1000),
            'provider' => $valueProvider = faker()->sentence(),
        ])->_disableAutoRefresh();

        WalletFactory::createMany(9);

        $response = $this->get('/api/v1/wallets/list');
        $response->assertStatusCode(Response::HTTP_OK);
        $content = $response->getData();
        $this->assertIsArray($content);
        $this->assertArrayHasKey('items', $content);
        $this->assertArrayHasKey('total', $content);

        $this->assertCount(10, $content['items']);
        $this->assertEquals(10, $content['total']);

        $firstItem = $content['items'][0] ?? null;
        $this->assertNotNull($firstItem);

        $this->assertArrayHasKey('phoneNumber', $firstItem);
        $this->assertEquals($entity->phoneNumber, $firstItem['phoneNumber']);

        $this->assertArrayHasKey('balance', $firstItem);
        $this->assertEquals($entity->balance, $firstItem['balance']);

        $this->assertArrayHasKey('provider', $firstItem);
        $this->assertEquals($entity->provider, $firstItem['provider']);

        $this->assertArrayHasKey('id', $firstItem);
        $this->assertEquals($entity->getId(), $firstItem['id']);
    }
}
