<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use App\Entity\OAuth2UserConsent;
use Doctrine\Common\Collections\Collection;

interface OAuth2UserInterface
{
    public function getId(): int;

    /** @return Collection<int, OAuth2UserConsent>|null */
    public function getConsents(): ?Collection;

    public function addConsent(OAuth2UserConsent $consent): void;
}
