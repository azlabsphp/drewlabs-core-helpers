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

namespace Drewlabs\Core\Helpers\Tests;

use Drewlabs\Core\Helpers\Reflector;
use Drewlabs\Core\Helpers\Tests\Stubs\FirstNameAware;
use Drewlabs\Core\Helpers\Tests\Stubs\NameAttribute;
use Drewlabs\Core\Helpers\Tests\Stubs\NameAware;
use Drewlabs\Core\Helpers\Tests\Stubs\Person;
use Drewlabs\Core\Helpers\Tests\Stubs\PersonInterface;
use PHPUnit\Framework\TestCase;

class ReflectorTest extends TestCase
{
    public function test_implements()
    {
        $this->assertTrue(Reflector::implements(Person::class, PersonInterface::class));
    }

    public function test_get_attributes()
    {
        $this->assertSame(NameAttribute::class, Reflector::getAttributes(Person::class)[0]->getName());
    }

    public function test_has_attribute()
    {
        $this->assertTrue(Reflector::hasAttribute(Person::class, NameAttribute::class));
    }

    public function test_get_attribute()
    {
        $this->assertInstanceOf(\ReflectionAttribute::class, Reflector::getAttribute(Person::class, NameAttribute::class));
        $this->assertNull(Reflector::getAttribute(Person::class, PersonInterface::class));
    }

    public function test_recursive_uses()
    {
        $this->assertTrue(\in_array(FirstNameAware::class, Reflector::usesRecursive(Person::class), true));
    }

    public function test_has_mixings()
    {
        $this->assertTrue(Reflector::hasMixins(Person::class, [NameAware::class, FirstNameAware::class]));
    }
}
