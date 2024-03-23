<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @template T
 * @extends  AbstractType<T>
 */
final class TokenType extends AbstractType
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setDisabled($options['disabled'])
            ->add(
                'token',
                TextType::class,
                ['label' => 'token/refreshToken'],
            )
            ->add('send', SubmitType::class)
        ;
    }
}
