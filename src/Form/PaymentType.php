<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'operationType',
                ChoiceType::class,
                [
                    'label' => 'payment ID',
                    'choices' => [
                        'Stripe' => 'stripe',
                        'CreditCard' => 'creditcard',
                    ],
                    'expanded' => true,
                ],
            )
            ->add(
                'amount',
                TextType::class,
                ['label' => 'Amount'],
            )
            ->add('yes', SubmitType::class)
            ->add('no', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Payment::class,
            ],
        );
    }
}
