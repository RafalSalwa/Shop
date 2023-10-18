<?php

namespace App\Controller;

use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/test', name: 'app_api_test', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the success response',
        content: new OA\JsonContent(type: "object", example: "{'foo': 'bar', 'hello': 'world'}")
    )]
    #[OA\Response(response: 401, ref: "#/components/responses/JwtTokenInvalid")]
    #[OA\Response(response: 404, description: "User not found", content: new OA\JsonContent(
        ref: "#/components/schemas/error"
    ))]
    #[Security(name: "Bearer")]
    #[OA\Tag(name: 'api_test')]
    public function user(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getEmail(),
        ]);
    }
}
