<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use App\Entity\OAuth2UserConsent;
use ArrayAccess;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;

interface OAuth2UserInterface
{
    /**
     * @return array<int, OAuth2UserConsent>|null
     *
     * @psalm-template   TKey of array-key
     * @psalm-template   T
     *
     * @template-extends ReadableCollection<TKey, T>
     * @template-extends ArrayAccess<TKey, T>
     */
    public function getConsents(): ?Collection;

    public function addConsent(OAuth2UserConsent $consent): void;
}
