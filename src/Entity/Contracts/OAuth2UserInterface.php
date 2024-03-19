<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use App\Entity\OAuth2UserConsent;
use Doctrine\Common\Collections\ArrayCollection;

interface OAuth2UserInterface
{
    /** @return ArrayCollection<int, OAuth2UserConsent>|null */
    public function getConsents(): ?ArrayCollection;

    public function addConsent(OAuth2UserConsent $consent): void;
}
