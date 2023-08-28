<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Exception\ProductNotFound;
use App\Exception\ProductStockDepleted;
use App\Exception\TooManySubscriptionsException;
use App\Manager\CartManager;
use App\Security\ProductVoter;
use App\Service\CartCalculator;
use App\Service\CartService;
use App\Service\ProductStockService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/cart/add/{type}/{id}', name: 'cart_add')]
    public function addToCart(
        #[ValueResolver('cart_item_type')] string $type,
        int                                       $id,
        CartService                               $cartService,
        EntityManagerInterface                    $entityManager,
        ProductStockService                       $productStockService,
        LockFactory                               $cartLockFactory
    ): RedirectResponse
    {
        $repository = $entityManager->getRepository($type);
        $entity = $repository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Item not found');
        }

        try {
            $lock = $cartLockFactory->createLock('cart_item_add');
            $lock->acquire(true);
            $this->denyAccessUnlessGranted(ProductVoter::ADD_TO_CART, $entity);
            $entityManager->getConnection()->beginTransaction();

            $productStockService->checkStockIsAvailable($entity);

            $item = $cartService->makeCartItem($entity);
            $cartService->checkSubscriptionsCount($item);
            $cart = $cartService->getCurrentCart();

            $cart->addItem($item);
            $cartService->save($cart);
            $productStockService->changeStock($entity, Product::STOCK_DECREASE);

            $entityManager->getConnection()->commit();
            $lock->release();
            
            $this->addFlash("info", "successfully added " . $entity->getDisplayName() . " to cart");
        } catch (ProductNotFound $pnf) {
            $this->addFlash("error", $pnf->getMessage());
            return $this->redirectToRoute($entity->getTypeName() . '_index', ['id' => $id, "page" => 1]);
        } catch (ProductStockDepleted $psd) {
            $this->addFlash("error", $psd->getMessage());
        } catch (AccessDeniedException $ade) {
            $this->addFlash("error", "You cannot add this product to cart with current subscription. Consider upgrade:)");
        } catch (TooManySubscriptionsException $subex) {
            $this->addFlash("error", $subex->getMessage());
        } catch (Exception $e) {
            $entityManager->getConnection()->rollback();
            throw $e;
        } finally {
            $lock->release();
        }

        return $this->redirectToRoute($entity->getTypeName() . '_details', ['id' => $id]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(CartItem $item, CartService $cartService, ProductStockService $productStockService): RedirectResponse
    {
        $cart = $cartService->getCurrentCart();
        try {
            if ($cart->getItems()->contains($item)) {
                $cart->getItems()->removeElement($item);
            }

            $productStockService->restoreStock($item, Product::STOCK_INCREASE);
            $cartService->save($cart);
            $this->addFlash("info", "successfully removed " . $item->getItemName() . " from cart");
        } catch (ProductNotFound $pnf) {
            $this->addFlash("error", $pnf->getMessage());
        } catch (ProductStockDepleted $psd) {
            $this->addFlash("error", $psd->getMessage());
        }
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/set_delivery_address', name: 'cart_delivery_address_set')]
    public function deliveryAddress(Request $request, CartService $cartService): Response
    {
        $deliveryAddressId = $request->request->get("addrId");
        $cartService->setDefaultDeliveryAddress($deliveryAddressId);
        return new Response("ok");
    }

    #[Route('/cart', name: 'cart_index')]
    public function show(Request $request, CartManager $cartManager, CartCalculator $cartCalculator): Response
    {
        $cart = $cartManager->getCurrentCart();
        $payment = $cartCalculator->calculatePayment($cart);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'payment' => $payment
        ]);
    }
}
