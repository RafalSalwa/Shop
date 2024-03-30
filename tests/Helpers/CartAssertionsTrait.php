<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use PHPUnit\Framework\Assert;
use Symfony\Component\DomCrawler\Crawler;

trait CartAssertionsTrait
{
    public static function assertCartItemsCountEquals(Crawler $crawler, int $expectedCount): void
    {
        $actualCount = $crawler
            ->filter('.cart-item')
            ->count()
        ;

        Assert::assertEquals(
            $expectedCount,
            $actualCount,
            sprintf('The cart should contain %d item(s). Actual: %d item(s).', $expectedCount, $actualCount)
        );
    }

    public static function assertCartIsEmpty(Crawler $crawler): void
    {
        $infoText = $crawler
            ->filter('.cart-items')
            ->getNode(0)
            ->textContent
        ;
        $infoText = self::normalizeWhitespace($infoText);
        Assert::assertEquals('Cart is empty', $infoText, 'The cart should be empty.');
    }

    public static function assertCartTotalEquals(Crawler $crawler, int $expectedTotal): void
    {
        $actualTotal = (float)$crawler
            ->filter('.cart-payment-total')
            ->getNode(0)
            ->textContent
        ;

        Assert::assertEquals(
            $expectedTotal,
            $actualTotal,
            sprintf('The cart total should be equal to %d €". Actual: %d.', $expectedTotal, $actualTotal)
        );
    }

    public static function assertCartContainsProductWithQuantity(
        Crawler $crawler,
        string $productName,
        int $expectedQuantity
    ): void {
        $actualQuantity = (int)self::getItemByProductName($crawler, $productName)
            ->filter('.cart-item-qty')
            ->getNode(0)
            ->textContent
        ;

        Assert::assertEquals(
            $expectedQuantity,
            $actualQuantity,
            sprintf('The quantity should be equal to %d. Actual: %d.', $expectedQuantity, $actualQuantity)
        );
    }

    public static function assertCartNotContainsProduct(Crawler $crawler, string $productName): void
    {
        Assert::assertEmpty(
            self::getItemByProductName($crawler, $productName),
            sprintf('The cart should not contain the product %s.', $productName)
        );
    }

    private static function normalizeWhitespace(string $value): string
    {
        return trim((string)preg_replace('/(?:\s{2,}+|[^\S ])/', ' ', $value));
    }

    private static function getItemByProductName(Crawler $crawler, string $productName): Crawler
    {
        $items = $crawler->filter('.cart-item')
            ->reduce(
                static function (Crawler $crawler) use ($productName): \Symfony\Component\DomCrawler\Crawler|false {
                    if ($crawler->filter('.cart-item-name')->getNode(0)->textContent === $productName) {
                        return $crawler;
                    }

                    return false;
                }
            )
        ;

        return $items->eq(0);
    }
}
