<?php

namespace Drewlabs\Core\Helpers\ValueObject;

class ModelTypeAttributeRValue extends \Drewlabs\Contracts\EntityObject\AbstractEntityObject
{
    /**
     * {@inheritDoc}
     */
    protected function getJsonableAttributes()
    {
        return [
            'attributes',
            'model'
        ];
    }
}
