<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\GRPC\VerificationCodeRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template T
 * @extends  AbstractType<T>
 */
final class UserVerifyCodeType extends AbstractType
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setDisabled($options['disabled'])
            ->add(
                'verificationCode',
                TextType::class,
                ['label' => 'code'],
            )
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => VerificationCodeRequest::class,
            ],
        );
    }
}
