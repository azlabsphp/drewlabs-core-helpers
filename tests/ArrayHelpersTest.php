<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class ArrayHelpersTest extends TestCase
{

    public function testArraySortMethod()
    {
        $testArray = ['Hello', 'World!', 'Java', 'Php'];
        \drewlabs_core_array_sort($testArray, function ($a, $b) {
            return strcmp($a, $b);
        });
        $this->assertEquals($testArray[0], 'Hello', 'Expect the first element of the sorted array to be Hello');
    }

    public function testIsArrayList()
    {
        $testArray = ['Hello', 'World!', 'Java', 'Php'];
        $this->assertTrue(!\drewlabs_core_array_is_array_list($testArray));
    }

    public function testArrayCombine()
    {
        $lhs = [
            [
                "author" => "Benedict Wayne",
                "book" => "Art of Politics"
            ],
            [
                "author" => "Marcel Vianey",
                "book" => "Design & Visual Arts"
            ]
        ];
        $rhs = [
            [
                "author" => "Michel Houston",
                "book" => "Psycology"
            ]
        ];

        $this->assertTrue(count(\drewlabs_core_array_combine($lhs, $rhs)) === 3, 'Expect total of the combined array to be 3');
    }

    public function testArraySearchmethod()
    {
        $list = [
            [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ]
        ];

        $this->assertEquals(\drewlabs_core_array_search([
            'lang' => 'PHP',
            'type' => 'Dynamic Language'
        ], $list), 0, 'Expect the matching key to be lang');
    }

    public function testArrayWhereFunction()
    {
        $list = [
            [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ],
            [
                'lang' => 'C++',
                'type' => 'Dynamic Language'
            ]
        ];
        $matches = \drewlabs_core_array_where($list, function($item) {
            return isset($item['lang']) && (($item['lang'] === 'C++') || ($item['lang'] === 'JAVA'));
        });
        $this->assertTrue(count($matches) === 2, 'Expect the returned result to be empty array');
    }

    public function testDrewlabsCoreArraySwap()
    {
        $name = 'NameProps';
        $value = 'Mountains';
        \drewlabs_core_array_swap($name, $value);
        $this->assertEquals($name, 'Mountains', 'Expects the name variable to equals Montains after swapping');
    }

    public function testArrayKeyExistsMethod()
    {
        $list = [
            'name' => 'WaterMelon',
            'qty' => 32,
            'price' => '$3'
        ];
        $this->assertTrue(\drewlabs_core_array_key_exists($list, 'name'), 'Expect the name key ti exists in the array');
    }

    public function testObjectToArrayFunction()
    {
        $person = new \stdClass;
        $person->name = 'Jean Paul';
        $address = new \stdClass;
        $physicalAddress = new \stdClass;
        $physicalAddress->street = '31, Bd des Clamidiases';
        $physicalAddress->houseNumber = 'No 192';
        $address->email = 'jeanpaul@example.com';
        $address->physical = $physicalAddress;
        $person->address = $address;
        $this->assertEquals(\drewlabs_core_array_object_to_array($person)['address']['email'], 'jeanpaul@example.com', 'Expect the generated array to have an inner array with email equals to jeanpaul@example.com');
    }

    public function testArrayHasFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ]
        ];
        $this->assertTrue(\drewlabs_core_array_has($list, 'php.lang'), 'Expect has to return true for lang');
    } //

    public function testArrayGetFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ]
        ];
        $this->assertEquals(\drewlabs_core_array_get($list, 'java.lang'), 'JAVA', 'Expect array get to return PHP as result');
    } // 
    public function testArraySetFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ]
        ];
        \drewlabs_core_array_set($list, 'java.lang', 'JEE');
        $this->assertEquals(\drewlabs_core_array_get($list, 'java.lang', 'JEE'), 'JEE', 'Expect array set to set the inner property lang to JEE');
    }

    public function testArrayContainsAll()
    {
        // printf(implode('', iterator_to_array(\drewlabs_core_array_iter([1, 2, 3, 4], function($item) {
        //     return sprintf("%d -> ", $item);
        // }))));
        $this->assertTrue(\drewlabs_core_array_contains_all(['Orange', 'Mangue', 'Banana', 'Paw-Paw'], ['Orange', 'Paw-Paw']), "Expect ['Orange', 'Mangue', 'Banana', 'Paw-paw'] to contains all values of ['Orange', 'Paw-Paw']");
    }

    public function testArrayMapFunction()
    {
        $list = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language'
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language'
            ],
            'python' => [
                'lang' => 'Python',
                'type' => 'Dynamic Language'
            ]
        ];
        $this->assertEquals(\drewlabs_core_array_map($list, function($i) {
            return $i['lang'];
        })['php'], 'PHP', "Expect first element of the mapped list to be PHP");
    }
}
