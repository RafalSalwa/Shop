<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ChangePasswordFormType extends AbstractType
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'options' => [
                        'attr' => ['autocomplete' => 'new-password'],
                    ],
                    'first_options' => [
                        'constraints' => [
                            new NotBlank(
                                ['message' => 'Please enter a password'],
                            ),
                            new Length(
                                [
                                    'min' => 6,
                                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                                ],
                            ),
                        ],
                        'label' => 'New password',
                    ],
                    'second_options' => ['label' => 'Repeat Password'],
                    'invalid_message' => 'The password fields must match.',
                    'mapped' => false,
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
