<?php

declare(strict_types=1);

namespace App\Tests\Functional\Wallet;

use App\Tests\Shared\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function Zenstruck\Foundry\faker;

class CreateWalletCommandControllerTest extends BaseWebTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function testCreateEntity(): void
    {
        $payload = [
            'phoneNumber' => faker()->sentence(),
            'balance' => faker()->randomFloat(2, 0, 1000),
            'provider' => faker()->sentence(),
        ];

        $response = $this->post('/api/v1/wallets/create/', $payload);
        $response->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $response->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $content = $response->getData();

        $this->assertArrayHasKey('id', $content);
        $this->assertNotNull($content['id']);

        $this->assertEquals($payload['phoneNumber'], $content['phoneNumber']);

        $this->assertEquals($payload['balance'], $content['balance']);

        $this->assertEquals($payload['provider'], $content['provider']);
    }
}
