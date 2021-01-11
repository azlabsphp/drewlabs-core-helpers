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

use PHPUnit\Framework\TestCase;

class ArrayHelpersTest extends TestCase
{
    public function testArraySortMethod()
    {
        $testArray = ['Hello', 'World!', 'Java', 'Php'];
        drewlabs_core_array_sort($testArray, static function ($a, $b) {
            return strcmp($a, $b);
        });
        $this->assertSame($testArray[0], 'Hello', 'Expect the first element of the sorted array to be Hello');
    }

    public function testIsArrayList()
    {
        $testArray = ['Hello', 'World!', 'Java', 'Php'];
        $this->assertTrue(!drewlabs_core_array_is_array_list($testArray));
    }

    public function testArrayCombine()
    {
        $lhs = [
            [
                'author' => 'Benedict Wayne',
                'book' => 'Art of Politics',
            ],
            [
                'author' => 'Marcel Vianey',
                'book' => 'Design & Visual Arts',
            ],
        ];
        $rhs = [
            [
                'author' => 'Michel Houston',
                'book' => 'Psycology',
            ],
        ];

        $this->assertTrue(3 === \count(drewlabs_core_array_combine($lhs, $rhs)), 'Expect total of the combined array to be 3');
    }

    public function testArraySearchmethod()
    {
        $list = [
            [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];

        $this->assertSame(drewlabs_core_array_search([
            'lang' => 'PHP',
            'type' => 'Dynamic Language',
        ], $list), 0, 'Expect the matching key to be lang');
    }

    public function testArrayWhereFunction()
    {
        $list = [
            [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
            [
                'lang' => 'C++',
                'type' => 'Dynamic Language',
            ],
        ];
        $matches = drewlabs_core_array_where($list, static function ($item) {
            return isset($item['lang']) && (('C++' === $item['lang']) || ('JAVA' === $item['lang']));
        });
        $this->assertTrue(2 === \count($matches), 'Expect the returned result to be empty array');
    }

    public function testDrewlabsCoreArraySwap()
    {
        $name = 'NameProps';
        $value = 'Mountains';
        drewlabs_core_array_swap($name, $value);
        $this->assertSame($name, 'Mountains', 'Expects the name variable to equals Montains after swapping');
    }

    public function testArrayKeyExistsMethod()
    {
        $list = [
            'name' => 'WaterMelon',
            'qty' => 32,
            'price' => '$3',
        ];
        $this->assertTrue(drewlabs_core_array_key_exists($list, 'name'), 'Expect the name key ti exists in the array');
    }

    public function testObjectToArrayFunction()
    {
        $person = new \stdClass();
        $person->name = 'Jean Paul';
        $address = new \stdClass();
        $physicalAddress = new \stdClass();
        $physicalAddress->street = '31, Bd des Clamidiases';
        $physicalAddress->houseNumber = 'No 192';
        $address->email = 'jeanpaul@example.com';
        $address->physical = $physicalAddress;
        $person->address = $address;
        $this->assertSame(drewlabs_core_array_object_to_array($person)['address']['email'], 'jeanpaul@example.com', 'Expect the generated array to have an inner array with email equals to jeanpaul@example.com');
    }

    public function testArrayHasFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];
        $this->assertTrue(drewlabs_core_array_has($list, 'php.lang'), 'Expect has to return true for lang');
    }

    public function testArrayGetFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];
        $this->assertSame(drewlabs_core_array_get($list, 'java.lang'), 'JAVA', 'Expect array get to return PHP as result');
    }

    public function testArraySetFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];
        drewlabs_core_array_set($list, 'java.lang', 'JEE');
        $this->assertSame(drewlabs_core_array_get($list, 'java.lang', 'JEE'), 'JEE', 'Expect array set to set the inner property lang to JEE');
    }

    public function testArrayContainsAll()
    {
        $this->assertTrue(drewlabs_core_array_contains_all(['Orange', 'Mangue', 'Banana', 'Paw-Paw'], ['Orange', 'Paw-Paw']), "Expect ['Orange', 'Mangue', 'Banana', 'Paw-paw'] to contains all values of ['Orange', 'Paw-Paw']");
    }

    public function testArrayMapFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];
        $this->assertSame(drewlabs_core_array_map($list, static function ($i) {
            return $i['lang'];
        })['php'], 'PHP', 'Expect first element of the mapped list to be PHP');
    }

    public function testArrayGroupCountFunction()
    {
        $texts = ['repeating', 'problematic', 'restating', 'repetition', 'act', 'many', 'repetition', 'single', 'word', 'problematic', 'many', 'message', 'repetition'];

        $result = drewlabs_core_array_group_count($texts);
        $this->assertSame($result['repetition'], 3, 'Expect the word repetition to appear 3 times in the source array');
    }
}
