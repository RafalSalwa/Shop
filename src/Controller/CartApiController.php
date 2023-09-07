<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ProductStockDepletedException;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

class CartApiController extends AbstractController
{
    #[Route('/api/cart', name: 'api_cart_index', methods: ['GET'])]
    #[OA\Tag(name: 'Cart')]
    #[Security(name: "Bearer")]
    public function index(CartManager $cartManager, SerializerInterface $serializer): JsonResponse
    {
        $cart = $cartManager->getCurrentCart();
        $serialized = $serializer->serialize(
            $cart, 'json',
            ['groups' => ['carts', 'cart_item']]
        );
        return JsonResponse::fromJsonString($serialized);
    }

    #[OA\Post(
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/prod_id")
        ),
        tags: ['Cart'],
        responses: [
            new  OA\Response(
                response: 200,
                description: 'Returns the success response',
                content: new OA\JsonContent(type: "object", example: "{'success': 'true'}")
            ),
            new OA\Response(
                response: 404,
                description: "Product not found",
                content: new OA\JsonContent(ref: "#/components/schemas/error")
            )
            ,
            new OA\Response(ref: "#/components/responses/JwtTokenInvalid", response: 401)
        ]
    )]
    #[Security(name: "Bearer")]
    #[Route(
        '/api/cart/add/product',
        name: 'api_cart_add',
        requirements: ["id" => Requirement::POSITIVE_INT],
        methods: ['POST']
    )]
    public function add(Request $request, ProductRepository $productRepository, CartService $cartService): Response
    {
        $params = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (!$params["id"]) {
            return new JsonResponse("prodID is missing in request", Response::HTTP_BAD_REQUEST);
        }

        try {
            $prodId = (int)$params["id"];

            /** @var Product $product */
            $product = $productRepository->find($prodId);
            if (!$product) {
                return $this->json([
                    'status' => "product not found",
                    'message' => sprintf("There is no such product with prvided ID #%s", $prodId)
                ], Response::HTTP_NOT_FOUND);
            }

            $cartService->addProduct($product);
        } catch (ProductStockDepletedException $e) {
            return $this->json([
                'status' => "something went wrong, please try again later",
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => "success",
        ]);
    }
}