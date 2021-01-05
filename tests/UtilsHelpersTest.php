<?php

namespace Drewlabs\Core\Helpers\Tests;

use Drewlabs\Core\Helpers\Tests\Stubs\Person;
use Drewlabs\Core\Helpers\Tests\Stubs\PersonValueObject;
use PHPUnit\Framework\TestCase;

class UtilsHelpersTest extends TestCase
{

    public function testComposeFunction()
    {
        $result = \drewlabs_core_fn_compose_array(
            function ($params) {
                return \drewlabs_core_strings_to_array(...$params);
            },
            function ($values) {
                return \drewlabs_core_array_group_count($values);
            },
            function ($value) {
                return \drewlabs_core_create_attribute_getter('repetition', null)($value);
            }
        )('repetition is the act of repeating or restating something more than once. In writing, repetition can occur at many levels: with individual letters and sounds, single words, phrases, or even ideas. repetition can be problematic in writing if it leads to dull work, but it can also be an effective poetic or rhetorical strategy to strengthen your message, as our examples of repetition in writing demonstrate.', ' ');
        $this->assertTrue(\is_int($result), 'Expects the test to complete successfully');
        $this->assertEquals($result, 4, 'Expect the word repetition to appear 4 times in the source array');
    }
    public function testReverseComposeFunction()
    {
        $result = \drewlabs_core_fn_reverse_compose_array(
            function ($value) {
                return \drewlabs_core_create_attribute_getter('repetition', null)($value);
            },
            function ($values) {
                return \drewlabs_core_array_group_count($values);
            },
            function ($params) {
                return \drewlabs_core_strings_to_array(...$params);
            }
        )('repetition is the act of repeating or restating something more than once. In writing, repetition can occur at many levels: with individual letters and sounds, single words, phrases, or even ideas. repetition can be problematic in writing if it leads to dull work, but it can also be an effective poetic or rhetorical strategy to strengthen your message, as our examples of repetition in writing demonstrate.', ' ');
        $this->assertTrue(\is_int($result), 'Expects the test to complete successfully');
        $this->assertEquals($result, 4, 'Expect the word repetition to appear 4 times in the source array');
    }

    public function testGetAttributeFunction()
    {
        $person = array(
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => "M"
        );
        $this->assertTrue(\drewlabs_core_get_attribute($person, 'sex', null) === "M", 'Expect the sex attribute of the person details to equals M');
        $this->assertTrue(is_null(\drewlabs_core_get_attribute($person, 'weight', null)), 'Expect weigth property to equals null');
    }

    public function testCreateAttributeGetterFunction()
    {
        $person = new \stdClass;
        $person->name = 'Jessica';
        $person->lastname = 'Campbell';
        $person->sex = 'F';
        $person->parent = new \stdClass;
        $person->parent->fullname = 'Morris Campbel';
        $person->parent->total_children = 3;
        $this->assertTrue(\drewlabs_core_create_attribute_getter('parent.total_children', null)($person) === 3, 'Expect the total_children property of the object parent property to equals 3');
    }

    public function testRecursiveGetAttributeFunction()
    {
        $person = array(
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => "M",
            "address" => [
                "house_number" => "H_so 9021",
                "street" => "HN 54",
                "email" => "hillairekoudossou@example.com",
                "postal_code" => 228
            ]
        );
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'address.email', null), 'hillairekoudossou@example.com',  'Expect the email attribute nested in the address field to equals hillairekoudossou@example.com');
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'address.postal_code', null), 228,  'Expect the postal_code attribute nested in the address field to equals 228');
    }

    public function testRecursiveSetAttributeFunction()
    {
        $person = array(
            'name' => 'Hillaire',
            'lastname' => 'Kodossou',
            'age' => 23,
            'sex' => "M",
            "address" => [
                "email" => "hillairekoudossou@example.com",
                "postal_code" => 228,
                "physical" => [
                    "house_number" => "H_so 9021",
                    "street" => "HN 54",
                ]
            ]
        );
        $person = \drewlabs_core_fn_compose(
            function ($p) {
                return \drewlabs_core_recursive_set_attribute($p, 'address.physical.house_number', 'H 492');
            },
            function ($p) {
                return \drewlabs_core_recursive_set_attribute($p, 'address.email', 'hkoudossou@example.com');
            },
            function ($p) {
                return \drewlabs_core_recursive_set_attribute($p, 'address.postal_code', 'BP 1515');
            }
        )($person);
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'address.physical.house_number', null), 'H 492',  'Expect the house_number attribute nested in the address field to equals H 492');
    }

    public function testCreateAttributeSetterFunction()
    {
        $person = new \stdClass;
        $address = new \stdClass;
        $address->email = 'hlordfera@example.com';
        $address->postal_code = 'BP 90778';
        $physical = new \stdClass;
        $physical->house_number = 'H7856';
        $physical->street = 'HN 78';
        $address->physical = $physical;
        $person->address = $address;
        $p = \drewlabs_core_create_attribute_setter(
            // 'address.physical.house_number',
            // 'H 492'
            [
                ['address.physical.house_number', 'H 492'],
                ['address.email', 'lordfera@gmail.com'],
                ['address.postal_code', 'BP 1515']
            ]
        )($person);
        // $this->assertEquals(\drewlabs_core_recursive_get_attribute($p, 'address.physical.house_number', null), 'H 492',  'Expect the house_number attribute nested in the address field to equals H 492');
        $this->assertNotEquals(\drewlabs_core_recursive_get_attribute($p, 'address.physical.house_number', null), \drewlabs_core_recursive_get_attribute($person, 'address.physical.house_number', null),  'Expect the modified person object to not equls the source person object');
    }



    public function testCreatePrivateAttributeSetterFunction()
    {
        $person = new Person;
        $person = \drewlabs_core_create_attribute_setter(
            'secret',
            'MySecureSecretPassword'
        )($person);
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'secret', null), 'MySecureSecretPassword',  'Expect new person password to equals MySecureSecretPassword');
    }

    public function testCreateValueObjectAttributeSetterFunction()
    {
        $person = new PersonValueObject([
            'login' => 'Asmyns14',
            'email' => 'asmyns.platonnas29@gmail.com'
        ]);
        $person = \drewlabs_core_create_attribute_setter(
            'secret',
            'SuperSecretPassword'
        )($person);
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'secret', null), 'SuperSecretPassword',  'Expect new person password to equals SuperSecretPassword');
    }

    public function testValueObjectCopyWithMethodCreateACopyOfTheObject()
    {
        $person = new PersonValueObject([
            'login' => 'Asmyns14',
            'email' => 'asmyns.platonnas29@gmail.com',
            'secret' => 'SuperSecretPassword'
        ]);
        $person2 = $person->copyWith([
           'login' => 'Azandrew'
        ]);
        $person = \drewlabs_core_create_attribute_setter(
            'secret',
            'Person1Secret'
        )($person);
        // $person->secret = 'Person1Secret';
        $this->assertEquals(\drewlabs_core_recursive_get_attribute($person, 'secret', null), \drewlabs_core_recursive_get_attribute($person2, 'secret', null),  'Expect person2 to be a deep copy of person, and any modification of person does not affect person 2');
    }
}
