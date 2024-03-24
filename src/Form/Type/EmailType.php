<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\ValueObject\EmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType as EmailBaseType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

use function iterator_to_array;

/** @template-extends AbstractType<string> */
final class EmailType extends AbstractType implements DataMapperInterface
{
    /** @param Traversable<int, FormInterface> $forms */
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }

        $forms = iterator_to_array($forms, false);
        $forms['email']->setData($viewData->toString());
    }

    /** @param Traversable<int, FormInterface> $forms */
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        $forms = iterator_to_array($forms, false);
        $viewData = new EmailAddress($forms['email']->getData());
    }

    public function getParent(): string
    {
        return EmailBaseType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'email_address';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            ['empty_data' => null],
        );
    }
}
