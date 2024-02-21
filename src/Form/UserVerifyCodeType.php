<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\EntityVerifyCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserVerifyCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'code',
                TextType::class,
                ['label' => 'code'],
            )
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => EntityVerifyCode::class,
            ],
        );
    }
}
