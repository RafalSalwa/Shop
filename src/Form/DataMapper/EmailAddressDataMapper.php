<?php

declare(strict_types=1);

namespace App\Form\DataMapper;

use App\Entity\Contracts\ShopUserInterface;
use App\ValueObject\EmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;
use Traversable;
use function dd;
use function iterator_to_array;

final class EmailAddressDataMapper extends AbstractType implements DataMapperInterface
{
    /**
     * @param ShopUserInterface|null $viewData
     * @param Traversable<int, FormInterface> $forms
     */
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        if (null === $viewData->getEmail()) {
            return;
        }
        $forms = iterator_to_array($forms);
        $forms['email']->setData($viewData->toString());
    }

    /** @param Traversable<int, FormInterface> $forms */
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        $forms = iterator_to_array($forms);
        $viewData = new EmailAddress($forms['email']->getData());
        dd($viewData);
    }
}
