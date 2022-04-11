<?php

namespace Drewlabs\Core\Helpers\Tests;

use Drewlabs\Core\Helpers\Reflector;
use Drewlabs\Core\Helpers\Tests\Stubs\NameAttribute;
use Drewlabs\Core\Helpers\Tests\Stubs\Person;
use Drewlabs\Core\Helpers\Tests\Stubs\PersonInterface;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use Drewlabs\Core\Helpers\Tests\Stubs\NameAware;
use Drewlabs\Core\Helpers\Tests\Stubs\FirstNameAware;

class ReflectorTest extends TestCase
{

    public function test_implements()
    {
        $this->assertTrue(Reflector::implements(Person::class, PersonInterface::class));
    }

    public function test_get_attributes()
    {
        $this->assertEquals(NameAttribute::class, Reflector::getAttributes(Person::class)[0]->getName());
    }

    public function test_has_attribute()
    {
        $this->assertTrue(Reflector::hasAttribute(Person::class, NameAttribute::class));
    }

    public function test_get_attribute()
    {
        $this->assertInstanceOf(ReflectionAttribute::class,  Reflector::getAttribute(Person::class, NameAttribute::class));
        $this->assertNull(Reflector::getAttribute(Person::class, PersonInterface::class));
    }

    public function test_recursive_uses()
    {
        $this->assertTrue(in_array(FirstNameAware::class, Reflector::usesRecursive(Person::class)));
    }

    public function test_has_mixings()
    {
        $this->assertTrue(Reflector::hasMixins(Person::class, [NameAware::class, FirstNameAware::class]));
    }
}
