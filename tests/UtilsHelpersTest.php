<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class UtilsHelpersTest extends TestCase
{

    public function testComposeFunction()
    {
        $result = \drewlabs_core_fn_compose(
            function ($params) {
                return \drewlabs_core_strings_to_array(...$params);
            },
            function ($values) {
                return \drewlabs_core_array_group_count($values);
            },
            function ($value) {
                return \drewlabs_core_create_attribute_getter()('repetition', null)($value);
            }
        )('repetition is the act of repeating or restating something more than once. In writing, repetition can occur at many levels: with individual letters and sounds, single words, phrases, or even ideas. repetition can be problematic in writing if it leads to dull work, but it can also be an effective poetic or rhetorical strategy to strengthen your message, as our examples of repetition in writing demonstrate.', ' ');
        $this->assertTrue(\is_int($result), 'Expects the test to complete successfully');
        $this->assertEquals($result, 4, 'Expect the word repetition to appear 4 times in the source array');
    }
    public function testReverseComposeFunction()
    {
        $result = \drewlabs_core_fn_reverse_compose(
            function ($value) {
                return \drewlabs_core_create_attribute_getter()('repetition', null)($value);
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
        $this->assertTrue(\drewlabs_core_create_attribute_getter()('parent.total_children', null)($person) === 3, 'Expect the total_children property of the object parent property to equals 3');
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
}
