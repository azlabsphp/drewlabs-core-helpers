<?php

namespace Drewlabs\Core\Helpers\Tests\Stubs;

trait NameAware
{
    use FirstNameAware;

    public function getName(): string
    {
        return 'GHISLAIN';
    }
}