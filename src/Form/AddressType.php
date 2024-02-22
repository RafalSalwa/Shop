<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'firstName',
                TextType::class,
                ['label' => 'First Name'],
            )
            ->add(
                'lastName',
                TextType::class,
                ['label' => 'Last Name'],
            )
            ->add(
                'addressLine1',
                TextType::class,
                ['label' => 'Address Line 1'],
            )
            ->add(
                'addressLine2',
                TextType::class,
                [
                    'label' => 'Address Line 2',
                    'required' => false,
                ],
            )
            ->add(
                'city',
                TextType::class,
                ['label' => 'City'],
            )
            ->add(
                'state',
                TextType::class,
                ['label' => 'State'],
            )
            ->add(
                'postalCode',
                TextType::class,
                ['label' => 'Postal Code'],
            )
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Address::class,
            ],
        );
    }
}
