<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Payment;
use App\Enum\PaymentProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

use function is_float;
use function iterator_to_array;

/**
 * @template T
 * @extends  AbstractType<T>
 */
final class PaymentType extends AbstractType implements DataMapperInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setDisabled($options['disabled'])
            ->setDataMapper($this)
            ->add(
                'operationNumber',
                TextType::class,
                [
                    'label' => 'Operation number',
                    'disabled' => true,
                ],
            )
            ->add(
                'amount',
                TextType::class,
                [
                    'label' => 'Amount',
                    'disabled' => true,
                ],
            )
            ->add(
                'operationType',
                EnumType::class,
                [
                    'class' => PaymentProvider::class,
                    'expanded' => true,
                    'disabled' => true,
                    'empty_data' => PaymentProvider::defaultValue(),
                ],
            )
            ->add('yes', SubmitType::class)
            ->add('no', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Payment::class,
            ],
        );
    }

    /**
     * @param Payment $viewData
     * @param Traversable<int, FormInterface> $forms
     */
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }
        $forms = iterator_to_array($forms);
        $forms['amount']->setData($viewData->getAmount() / 100);
        $forms['operationType']->setData($viewData->getOperationType());
        $forms['operationNumber']->setData($viewData->getOperationNumber());
    }

    /**
     * @param Payment $viewData
     * @param Traversable<int, FormInterface> $forms
     */
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        if (true !== is_float($viewData->getAmount())) {
            return;
        }
        $forms = iterator_to_array($forms);
        $viewData->setAmount($forms['amount']->getData() * 100);
    }
}
