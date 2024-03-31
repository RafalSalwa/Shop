<?php

declare(strict_types=1);

namespace App\Controller\OAuth2Api;

use App\Controller\AbstractShopController;
use App\Exception\Contracts\CartOperationExceptionInterface;
use App\Exception\Contracts\StockOperationExceptionInterface;
use App\Exception\ProductStockDepletedException;
use App\Service\CartService;
use App\Workflow\CartWorkflow;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

#[AsController]
#[Route(path: '/api/cart', name: 'api_cart_', methods: ['GET', 'POST', 'PUT'])]
final class OAuthCartApiController extends AbstractShopController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    #[OA\Tag(name: 'Cart')]
    #[Security(name: 'Bearer')]
    public function index(CartService $cartService, SerializerInterface $serializer): JsonResponse
    {
        $cart = $cartService->getCurrentCart();
        $serialized = $serializer->serialize($cart, 'json', ['groups' => ['carts', 'cart_item']]);

        return new JsonResponse($serialized);
    }

    #[OA\Post(
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/prod_id'),
        ),
        tags: ['Cart'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the success response',
                content: new OA\JsonContent(type: 'object', example: "{'success': 'true'}"),
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(ref: '#/components/schemas/error'),
            ),
            new OA\Response(ref: '#/components/responses/JwtTokenInvalid', response: 401),
        ],
    )]
    #[Security(name: 'Bearer')]
    #[Route(
        path: '/add/product',
        name: 'add',
        requirements: [
            'id' => Requirement::POSITIVE_INT,
        ],
        methods: ['POST'],
    )]
    public function add(Request $request, CartWorkflow $cartWorkflow): Response
    {
        $params = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (null === $params['id']) {
            return new JsonResponse('prodID is missing in request', Response::HTTP_BAD_REQUEST);
        }

        try {
            $prodId = (int)$params['id'];

            $cartWorkflow->add($prodId, 1);
        } catch (ProductStockDepletedException $exception) {
            return $this->json(
                [
                    'status' => 'something went wrong, please try again later',
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND,
            );
        } catch (CartOperationExceptionInterface | StockOperationExceptionInterface $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(
            ['status' => 'success'],
        );
    }
}
