<?php

namespace App\Form\DataMapper;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;

class EmailAddressDataMapper extends AbstractType implements DataMapperInterface
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
