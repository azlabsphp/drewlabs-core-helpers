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

use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Core\Helpers\Functional;
use Drewlabs\Core\Helpers\Reflector;
use Drewlabs\Core\Helpers\Str;
use Drewlabs\Core\Helpers\Tests\Stubs\Person;
use Drewlabs\Core\Helpers\Tests\Stubs\PersonValueObject;
use PHPUnit\Framework\TestCase;

class UtilsHelpersTest extends TestCase
{
    public function testComposeFunction()
    {
        $result = Functional::compose(
            static function ($params) {
                return Str::split(...$params);
            },
            static function ($values) {
                return Arr::groupCount($values);
            },
            static function ($value) {
                return Reflector::propertyGetter('repetition', null)($value);
            }
        )(
            'repetition is the act of repeating or restating something more than once. In writing, repetition can occur at many levels: with individual letters and sounds, single words, phrases, or even ideas. repetition can be problematic in writing if it leads to dull work, but it can also be an effective poetic or rhetorical strategy to strengthen your message, as our examples of repetition in writing demonstrate.',
            ' '
        );
        $this->assertIsInt($result, 'Expects the test to complete successfully');
        $this->assertSame($result, 4, 'Expect the word repetition to appear 4 times in the source array');
    }

    public function testReverseComposeFunction()
    {
        $result = Functional::rcompose(
            static function ($value) {
                return Reflector::propertyGetter('repetition', null)($value);
            },
            static function ($values) {
                return Arr::groupCount($values);
            },
            static function ($params) {
                return Str::split(...$params);
            }
        )('repetition is the act of repeating or restating something more than once. In writing, repetition can occur at many levels: with individual letters and sounds, single words, phrases, or even ideas. repetition can be problematic in writing if it leads to dull work, but it can also be an effective poetic or rhetorical strategy to strengthen your message, as our examples of repetition in writing demonstrate.', ' ');
        $this->assertIsInt($result, 'Expects the test to complete successfully');
        $this->assertSame($result, 4, 'Expect the word repetition to appear 4 times in the source array');
    }

    public function testGetAttributeFunction()
    {
        $person = [
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => 'M',
        ];
        $this->assertTrue('M' === Reflector::getPropertyValue($person, 'sex', null), 'Expect the sex attribute of the person details to equals M');
        $this->assertTrue(null === Reflector::getPropertyValue($person, 'weight', null), 'Expect weigth property to equals null');
    }

    public function testCreateAttributeGetterFunction()
    {
        $person = new \stdClass();
        $person->name = 'Jessica';
        $person->lastname = 'Campbell';
        $person->sex = 'F';
        $person->parent = new \stdClass();
        $person->parent->fullname = 'Morris Campbel';
        $person->parent->total_children = 3;
        $this->assertTrue(3 === Reflector::propertyGetter('parent.total_children', null)($person), 'Expect the total_children property of the object parent property to equals 3');
    }

    public function testRecursiveGetAttributeFunction()
    {
        $person = [
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => 'M',
            'address' => [
                'house_number' => 'H_so 9021',
                'street' => 'HN 54',
                'email' => 'hillairekoudossou@example.com',
                'postal_code' => 228,
            ],
        ];
        $this->assertSame(Reflector::getPropertyValue($person, 'address.email', null), 'hillairekoudossou@example.com', 'Expect the email attribute nested in the address field to equals hillairekoudossou@example.com');
        $this->assertSame(Reflector::getPropertyValue($person, 'address.postal_code', null), 228, 'Expect the postal_code attribute nested in the address field to equals 228');
    }

    public function testRecursiveSetAttributeFunction()
    {
        $person = [
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => 'M',
            'address' => [
                'email' => 'hillairekoudossou@example.com',
                'postal_code' => 228,
                'physical' => [
                    'house_number' => 'H_so 9021',
                    'street' => 'HN 54',
                ],
            ],
        ];
        $person = Functional::compose(
            static function ($p) {
                return Reflector::getPropertyValue($p, 'address.physical.house_number', 'H 492');
            },
            static function ($p) {
                return Reflector::getPropertyValue($p, 'address.email', 'hkoudossou@example.com');
            },
            static function ($p) {
                return Reflector::getPropertyValue($p, 'address.postal_code', 'BP 1515');
            }
        )($person);
        $this->assertSame(Reflector::getPropertyValue($person, 'address.physical.house_number', null), 'H 492', 'Expect the house_number attribute nested in the address field to equals H 492');
    }

    public function testCreateAttributeSetterFunction()
    {
        $person = new \stdClass();
        $address = new \stdClass();
        $address->email = 'hlordfera@example.com';
        $address->postal_code = 'BP 90778';
        $physical = new \stdClass();
        $physical->house_number = 'H7856';
        $physical->street = 'HN 78';
        $address->physical = $physical;
        $person->address = $address;
        $p = Reflector::propertySetter(
            // 'address.physical.house_number',
            // 'H 492'
            [
                ['address.physical.house_number', 'H 492'],
                ['address.email', 'lordfera@gmail.com'],
                ['address.postal_code', 'BP 1515'],
            ]
        )($person);
        $this->assertNotSame(Reflector::getPropertyValue($p, 'address.physical.house_number', null), Reflector::getPropertyValue($person, 'address.physical.house_number', null), 'Expect the modified person object to not equls the source person object');
    }

    public function testCreatePrivateAttributeSetterFunction()
    {
        $person = new Person();
        $person = Reflector::propertySetter(
            'secret',
            'MySecureSecretPassword'
        )($person);
        $this->assertSame(Reflector::getPropertyValue($person, 'secret', null), 'MySecureSecretPassword', 'Expect new person password to equals MySecureSecretPassword');
    }

    public function testCreateValueObjectAttributeSetterFunction()
    {
        $person = new PersonValueObject([
            'login' => 'Asmyns14',
            'email' => 'asmyns.platonnas29@gmail.com',
        ]);
        $person = Reflector::propertySetter(
            'secret',
            'SuperSecretPassword'
        )($person);
        $this->assertSame(Reflector::getPropertyValue($person, 'secret', null), 'SuperSecretPassword', 'Expect new person password to equals SuperSecretPassword');
    }

    public function testValueObjectCopyWithMethodCreateACopyOfTheObject()
    {
        $person = new PersonValueObject([
            'login' => 'Asmyns14',
            'email' => 'asmyns.platonnas29@gmail.com',
            'secret' => 'SuperSecretPassword',
        ]);
        $person2 = $person->copyWith([
            'login' => 'Azandrew',
            'email' => 'azandrewdevelopper@gmail.com',
        ]);
        $person = Reflector::propertySetter(
            'secret',
            'Person1Secret'
        )($person);
        $this->assertNotSame(Reflector::getPropertyValue($person, 'secret', null), Reflector::getPropertyValue($person2, 'secret', null), 'Expect person2 to be a deep copy of person, and any modification of person does not affect person 2');
        $this->assertSame(Reflector::getPropertyValue($person2, 'login', null), 'Azandrew', 'Expect person2 login to equals Azandrew');
    }
}
