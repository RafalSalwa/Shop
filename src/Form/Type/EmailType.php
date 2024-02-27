<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;

class EmailType extends AbstractType implements DataMapperInterface
{

    /**
     * @inheritDoc
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        // TODO: Implement mapDataToForms() method.
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        // TODO: Implement mapFormsToData() method.
    }
}
