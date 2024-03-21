<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Contracts\ShopUserInterface;
use App\ValueObject\EmailAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Traversable;
use function iterator_to_array;

/**
 * @template T
 * @extends  AbstractType<T>
 */
final class RegistrationFormType extends AbstractType implements DataMapperInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setDisabled($options['disabled'])
            ->setDataMapper($this)
            ->add(
                'email',
                EmailType::class,
                [
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'username'],
                ],
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue(
                            ['message' => 'You should agree to our terms.'],
                        ),
                    ],
                ],
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'attr' => ['autocomplete' => 'new-password'],
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
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
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'attr' => ['class' => 'bg-white  rounded-5 shadow-5-strong p-5'],
                'empty_data' => null,
            ],
        );
    }

    /**
     * @param ShopUserInterface|null $viewData
     * @param Traversable<int, FormInterface> $forms
     */
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }
        $forms = iterator_to_array($forms);
        $forms['email']->setData($viewData);
    }

    /** @param Traversable<int, FormInterface> $forms */
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        $forms = iterator_to_array($forms);
        $viewData = new EmailAddress($forms['email']->getData());
    }
}
