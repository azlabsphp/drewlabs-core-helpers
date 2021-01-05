<?php

namespace Drewlabs\Core\Helpers\Tests\Stubs;

use Drewlabs\Contracts\EntityObject\AbstractEntityObject;

final class PersonValueObject extends AbstractEntityObject
{
    protected function getJsonableAttributes()
    {
        return [
            'login',
            'email',
            'secret'
        ];
    }
}