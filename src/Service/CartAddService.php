<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Exception\ProductNotFound;
use App\Exception\ProductStockDepletedException;
use App\Exception\TooManySubscriptionsException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartAddService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductStockService $productStockService,
        private readonly LockFactory $cartLockFactory,
        private readonly CartService $cartService,
    ) {}

    public function addToCart(string $type, int $id)
    {
        $entityRepository = $this->entityManager->getRepository($type);
        $entity = $entityRepository->find($id);
        if (! $entity) {
            throw $this->createNotFoundException('Item not found');
        }

        try {
            $lock = $this->cartLockFactory->createLock('cart_item_add');
            $lock->acquire(true);
            $this->entityManager->getConnection()
                ->beginTransaction()
            ;

            $this->productStockService->checkStockIsAvailable($entity);

            $item = $this->cartService->makeCartItem($entity);
            $this->cartService->checkSubscriptionsCount($item);
            $cart = $this->cartService->getCurrentCart();

            $cart->addItem($item);
            $this->cartService->save($cart);
            $this->productStockService->changeStock($entity, Product::STOCK_DECREASE);

            $this->entityManager->getConnection()
                ->commit()
            ;
            $lock->release();

            $this->addFlash('info', 'successfully added ' . $entity->getDisplayName() . ' to cart');
        } catch (ProductNotFound $pnf) {
            $this->addFlash('error', $pnf->getMessage());

            return $this->redirectToRoute(
                $entity->getTypeName() . '_index',
                [
                    'id' => $id,
                    'page' => 1,
                ],
            );
        } catch (ProductStockDepletedException $psd) {
            $this->addFlash('error', $psd->getMessage());
        } catch (AccessDeniedException) {
            $this->addFlash(
                'error',
                'You cannot add this product to cart with current subscription. Consider upgrade:)',
            );
        } catch (TooManySubscriptionsException $subex) {
            $this->addFlash('error', $subex->getMessage());
        } catch (Exception $e) {
            $this->entityManager->getConnection()
                ->rollback()
            ;

            throw $e;
        } finally {
            $lock->release();
        }
    }
}
