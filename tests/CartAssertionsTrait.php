<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\Assert;
use Symfony\Component\DomCrawler\Crawler;

trait CartAssertionsTrait
{
    public static function assertCartItemsCountEquals(Crawler $crawler, int $expectedCount): void
    {
        $actualCount = $crawler
            ->filter('.cart-item')
            ->count();

        Assert::assertEquals(
            $expectedCount,
            $actualCount,
            "The cart should contain \"{$expectedCount}\" item(s). Actual: \"{$actualCount}\" item(s)."
        );
    }

    public static function assertCartIsEmpty(Crawler $crawler)
    {
        $infoText = $crawler
            ->filter('.cart-items')
            ->getNode(0)
            ->textContent;
        $infoText = self::normalizeWhitespace($infoText);
        Assert::assertEquals('Cart is empty', $infoText, 'The cart should be empty.');
    }

    public static function assertCartTotalEquals(Crawler $crawler, $expectedTotal)
    {
        $actualTotal = (float) $crawler
            ->filter('.cart-payment-total')
            ->getNode(0)
            ->textContent;

        Assert::assertEquals(
            $expectedTotal,
            $actualTotal,
            "The cart total should be equal to \"{$expectedTotal} €\". Actual: \"{$actualTotal} €\"."
        );
    }

    public static function assertCartContainsProductWithQuantity(
        Crawler $crawler,
        string $productName,
        int $expectedQuantity
    ): void {
        $actualQuantity = (int) self::getItemByProductName($crawler, $productName)
            ->filter('.cart-item-qty')
            ->getNode(0)
            ->textContent;

        Assert::assertEquals(
            $expectedQuantity,
            $actualQuantity,
            "The quantity should be equal to \"{$expectedQuantity}\". Actual: \"{$actualQuantity}\"."
        );
    }

    public static function assertCartNotContainsProduct(Crawler $crawler, string $productName): void
    {
        Assert::assertEmpty(
            self::getItemByProductName($crawler, $productName),
            "The cart should not contain the product \"{$productName}\"."
        );
    }

    private static function normalizeWhitespace(string $value): string
    {
        return trim(preg_replace('/(?:\s{2,}+|[^\S ])/', ' ', $value));
    }

    private static function getItemByProductName(Crawler $crawler, string $productName)
    {
        $items = $crawler->filter('.cart-item')
            ->reduce(
                function (Crawler $node) use ($productName) {
                    if ($node->filter('.cart-item-name')->getNode(0)->textContent === $productName) {
                        return $node;
                    }

                    return false;
                }
            );

        return empty($items) ? null : $items->eq(0);
    }
}
