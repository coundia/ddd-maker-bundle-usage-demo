<?php

declare(strict_types=1);

namespace App\Core\Presentation\Controller;

use App\Core\Application\DTO\WalletRequestDTO;
use App\Shared\Domain\DTO\ApiResponseDTO;
use App\Shared\Domain\DTO\ErrorResponseDTO;
use App\Shared\Domain\Response\Response;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/wallets/create/', name: 'api_command_v1_wallet_create', methods: ['POST'])]
#[OA\Post(
    path: '/api/v1/wallets/create/',
    summary: 'Creates a new Wallet item.',
    requestBody: new OA\RequestBody(
        description: 'Data required to create a Wallet.',
        required: true,
        content: new Model(type: WalletRequestDTO::class, groups: ['default'])
    ),
    tags: ['Wallets'],
    responses: [
        new OA\Response(
            response: 201,
            description: 'Wallet created successfully.',
            content: new Model(type: ApiResponseDTO::class, groups: ['default'])
        ),
        new OA\Response(
            response: 400,
            description: 'Invalid input.',
            content: new Model(type: ErrorResponseDTO::class, groups: ['error'])
        ),
    ]
)]
class CreateWalletController extends \App\Shared\Presentation\Controller\BaseController
{
    public function __construct(
        private \App\Shared\Application\Bus\CommandBus $command_bus,
        private \App\Core\Application\Mapper\Wallet\WalletMapperInterface $mapper
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $command_model = $this->mapper->fromArray($data);
            $command = new \App\Core\Application\Command\CreateWalletCommand(
                phoneNumber: $command_model->phoneNumber,
                balance: $command_model->balance,
                provider: $command_model->provider,
            );
            $model = $this->command_bus->dispatch($command);
            $responseDTO = $this->mapper->toArray($model);

            return Response::successResponse($responseDTO, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return Response::errorResponse($e->getMessage());
        }
    }
}
