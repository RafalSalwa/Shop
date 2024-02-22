<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'operationNumber',
                TextType::class,
                ['label' => 'payment ID'],
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
