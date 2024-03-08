<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'First Name',
                    'attr' => ['placeholder' => 'John'],
                ],
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Last Name',
                    'attr' => ['placeholder' => 'Doe'],
                ],
            )
            ->add(
                'phoneNo',
                TextType::class,
                ['label' => 'Mobile No', 'attr' => ['placeholder' => '123-456-789']],
            )
            ->add(
                'addressLine1',
                TextType::class,
                ['label' => 'Address Line 1', 'attr' => ['placeholder' => 'Street 123']],
            )
            ->add(
                'addressLine2',
                TextType::class,
                [
                    'label' => 'Address Line 2',
                    'attr' => ['placeholder' => 'Street 123'],
                    'required' => false,
                ],
            )
            ->add(
                'country',
                TextType::class,
                ['label' => 'Country', 'attr' => ['placeholder' => 'United States']],
            )
            ->add(
                'city',
                TextType::class,
                ['label' => 'City', 'attr' => ['placeholder' => 'New York']],
            )
            ->add(
                'state',
                TextType::class,
                ['label' => 'State', 'attr' => ['placeholder' => 'New York']],
            )
            ->add(
                'postalCode',
                TextType::class,
                ['label' => 'Postal Code', 'attr' => ['placeholder' => '00000']],
            )
            ->add('save', SubmitType::class);
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
