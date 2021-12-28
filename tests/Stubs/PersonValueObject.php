<?php

declare(strict_types=1);

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Core\Helpers\Tests\Stubs;

use Drewlabs\Contracts\EntityObject\AbstractEntityObject;

final class PersonValueObject
{

    public function __construct($attributes = [])
    {
        $this->copyWith($attributes);
    }

    public function copyWith($attributes)
    {
        $self = clone $this;
        foreach ($this->getJsonableAttributes() as $key) {
            if (array_key_exists($key, $attributes)) {
                $self->{$key} = $attributes[$key];
            }
        }
        return $self;
    }
    protected function getJsonableAttributes()
    {
        return [
            'login',
            'email',
            'secret',
        ];
    }
}
