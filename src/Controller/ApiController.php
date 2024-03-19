<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/api', name: 'api_')]
final class ApiController extends AbstractShopController
{
    #[Route(path: '/test', name: 'test', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the success response',
        content: new OA\JsonContent(type: 'object', example: "{'foo': 'bar', 'hello': 'world'}"),
    )]
    #[OA\Response(ref: '#/components/responses/JwtTokenInvalid', response: 401)]
    #[OA\Response(
        response: 404,
        description: 'User not found',
        content: new OA\JsonContent(
            ref: '#/components/schemas/error',
        ),
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'api_test')]
    public function user(): Response
    {
        $user = $this->getShopUser();

        return $this->json(
            [
                'message' => 'You successfully authenticated!',
                'email'   => $user->getEmail(),
            ],
        );
    }
}
