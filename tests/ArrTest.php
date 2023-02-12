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
use Drewlabs\Core\Helpers\Iter;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testArraySortMethod()
    {
        $testArray = ['Hello', 'World!', 'Java', 'Php'];
        Arr::sort($testArray, static function ($a, $b) {
            return strcmp($a, $b);
        });
        $this->assertSame($testArray[0], 'Hello', 'Expect the first element of the sorted array to be Hello');
    }

    public function testIsArrayList()
    {
        $testArray = [['Hello'], ['World!'], ['Java'], ['Php']];
        $this->assertTrue(Arr::isnotassoclist($testArray));
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

        $this->assertTrue(3 === \count(Arr::combine($lhs, $rhs)), 'Expect total of the combined array to be 3');
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

        $this->assertSame(Arr::search([
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
        $matches = Arr::where($list, static function ($item) {
            return isset($item['lang']) && (('C++' === $item['lang']) || ('JAVA' === $item['lang']));
        });
        $this->assertTrue(2 === \count($matches), 'Expect the returned result to be empty array');
    }

    public function testDrewlabsCoreArraySwap()
    {
        $name = 'NameProps';
        $value = 'Mountains';
        Arr::swap($name, $value);
        $this->assertSame($name, 'Mountains', 'Expects the name variable to equals Montains after swapping');
    }

    public function testArrayKeyExistsMethod()
    {
        $list = [
            'name' => 'WaterMelon',
            'qty' => 32,
            'price' => '$3',
        ];
        $this->assertTrue(Arr::keyExists($list, 'name'), 'Expect the name key ti exists in the array');
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
        $this->assertSame(Arr::udtToArray($person)['address']['email'], 'jeanpaul@example.com', 'Expect the generated array to have an inner array with email equals to jeanpaul@example.com');
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
        $this->assertTrue(Arr::has($list, 'php.lang'), 'Expect has to return true for lang');
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
        $this->assertSame(Arr::get($list, static function ($values) {
            return $values['java']['lang'];
        }), 'JAVA', 'Expect array get to return PHP as result');
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
        Arr::set($list, 'java.lang', 'JEE');
        $this->assertSame(Arr::get($list, 'java.lang', 'JEE'), 'JEE', 'Expect array set to set the inner property lang to JEE');
    }

    public function testArrayContainsAll()
    {
        $this->assertTrue(Arr::containsAll(['Orange', 'Mangue', 'Banana', 'Paw-Paw'], ['Orange', 'Paw-Paw']), "Expect ['Orange', 'Mangue', 'Banana', 'Paw-paw'] to contains all values of ['Orange', 'Paw-Paw']");
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
        $this->assertSame(Arr::map($list, static function ($i) {
            return $i['lang'];
        })['php'], 'PHP', 'Expect first element of the mapped list to be PHP');
    }

    public function testArrayGroupCountFunction()
    {
        $texts = ['repeating', 'problematic', 'restating', 'repetition', 'act', 'many', 'repetition', 'single', 'word', 'problematic', 'many', 'message', 'repetition'];

        $result = Arr::groupCount($texts);
        $this->assertSame($result['repetition'], 3, 'Expect the word repetition to appear 3 times in the source array');
    }

    public function testZipMethod()
    {
        $list = Arr::szip((object) ['one' => 1, 'two' => 2, 'thee' => 3], [4, 5, 6], [7, 8, 9]);
        $this->assertTrue(Arr::isArrayable($list), 'Expect the returned list ot be an array');
        $this->assertSame(\count($list[0]), 3, 'Expect the total items in the first index of the list to have a total length equals 3');
        $this->assertTrue(1 === $list[0][0], 'Expect first item of the list first element to equal 1');
    }

    public function testIteratorMap()
    {
        $list = new \ArrayIterator([
            'english' => 'Hello!',
            'french' => 'Salut!',
            'spanish' => 'Hola!',
            'latin' => 'Salve!',
            'german' => 'Guten Tag!',
        ]);

        $values = Iter::map($list, static function ($item) {
            return strtoupper($item);
        }, true);

        return $this->assertInstanceOf(\Traversable::class, $values, 'Expect Iter::map to return an Array Iterator');
    }

    public function testIteratorReduceFunction()
    {
        $list = new \ArrayIterator([1, 2, 3, 4, 5]);

        $result = Iter::reduce($list, static function ($carry, $item) {
            return $carry + $item;
        }, 0);

        return $this->assertTrue(15 === $result);
    }

    public function testIsAssocFunction()
    {
        $array = [
            'php' => [
                'lang' => 'PHP',
                'type' => 'Dynamic Language',
            ],
            'java' => [
                'lang' => 'JAVA',
                'type' => 'Statically Type Language',
            ],
            [
                'lang' => 'Python',
                'type' => 'Dynamic Language',
            ],
        ];
        $this->assertFalse(Arr::isallassoc($array), 'Expect the array to not be an associative array');
    }

    public function testBinarySearchFunction()
    {
        $array = [
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
        sort($array);
        $this->assertSame(
            Arr::bsearch($array, 'JAVA', static function ($curr, $item) {
                if (0 === strcmp($curr['lang'], $item)) {
                    return 0;
                }
                return strcmp($curr['lang'], $item) > 0 ? -1 : 1;
            }),
            0,
            'Expect Arr::bsearch function to return 0'
        );
    }

    public function testArrayOnlyFunction()
    {
        $test_array = [
            'user' => 'admin',
            'password' => 'homestead',
            'host' => '127.0.0.1',
            'port' => 22,
        ];
        $result = Arr::only($test_array, ['password']);
        $this->assertTrue(4 !== \count($result), 'Expect test to fail');
        $this->assertCount(1, $result, 'Assert only on item in array');
        $this->assertTrue(\in_array('password', array_keys($result), true), 'Assert password key is in result array');

        // Test filtering on value

        $result = Arr::only($test_array, ['127.0.0.1', 'user'], false);
        $this->assertTrue(4 !== \count($result), 'Expect test to fail');
        $this->assertCount(1, $result, 'Assert only on item in array');
        $this->assertTrue(\in_array('host', array_keys($result), true), 'Assert password key is in result array');
        $this->assertTrue(!\in_array('user', array_keys($result), true), 'Test for fail case');
    }

    public function testArrayRemove()
    {
        $arr = range(0, 10);
        Arr::remove($arr, [4, 6]);
        $this->assertCount(9, $arr);
    }

    public function testArrayExcept()
    {
        $arr = range(0, 9);
        $arr2 = Arr::except($arr, [0, 4, 8]);
        $this->assertCount(7, $arr2);
    }

    public function testZip()
    {
        $arr = range(0, 9);
        $arr2 = range(10, 19);
        $zip = Arr::zip($arr, $arr2);
        $this->assertSame([0, 10], $zip[0]);
        $this->assertSame([9, 19], Arr::last($zip));
    }

    public function testUniqe()
    {
        $arr = range(0, 20);
        $arr[] = 19;
        $arr[] = 15;
        $this->assertSame(range(0, 20), Arr::unique($arr));
    }

    public function testCreate_Array_From_Traversable()
    {
        $iterator = new \ArrayIterator(range(0, 10));
        $this->assertSame(range(0, 10), Arr::create($iterator));
    }

    public function testCreate_Array_From_Object_With_ToArray()
    {
        $object = new class()
        {
            public function toArray()
            {
                return range(0, 10);
            }
        };
        $this->assertSame(range(0, 10), Arr::create($object));
    }

    public function testCreate_Array_From_IteratorAggregate()
    {
        $object = new class() implements \IteratorAggregate
        {
            public function getIterator(): \Traversable
            {
                return new \ArrayIterator(range(0, 10));
            }
        };
        $this->assertSame(range(0, 10), Arr::create($object));
    }

    public function testCreate_Array_From_String_And_Number()
    {
        $val = 3;
        $strval = 'Hello World!';
        $this->assertSame([3], Arr::create($val));
        $this->assertSame(['Hello World!'], Arr::create($strval));
    }


    public function  test_array_isassoclist()
    {
        $this->assertTrue(Arr::isassoclist([
            'basic' =>  ['Hello', 'World'],
            'greetings' =>  ['Good', 'Morning']
        ]));
        $this->assertFalse(Arr::isassoclist([
            ['Hello', 'World']
        ]));
        $this->assertFalse(Arr::isassoclist(['Hello', 'World']));
        $this->assertFalse(Arr::isassoclist([]));
    }

    public function test_array_isnotassoclist()
    {
        $this->assertFalse(Arr::isnotassoclist([
            'basic' =>  ['Hello', 'World'],
            'greetings' =>  ['Good', 'Morning']
        ]));
        $this->assertTrue(Arr::isnotassoclist([
            ['Hello', 'World']
        ]));
        $this->assertFalse(Arr::isnotassoclist(['Hello', 'World']));
        $this->assertFalse(Arr::isnotassoclist([]));
    }

    public function test_array_recursive_ksort()
    {
        $arr = [
            'fruits' => [
                [
                    'weight' => '30pd',
                    'name' => 'Banana'
                ],
                [
                    'name' => 'Orange',
                    'weight' => '10pd',
                ],
                [
                    'name' => 'Apple',
                    'weight' => '20pd',
                ]
            ],
            'articles' => [
                [
                    'price' => 120,
                    'name' => 'HTC',
                ],
                [
                    'price' => 500,
                    'name' => 'LG SMART 100 TV',
                ],
                [
                    'price' => 10,
                    'name' => 'HUAWEI LED CONTROLLER',
                ]
            ]
        ];
        $sorted = Arr::recursiveksort($arr);
        $this->assertEquals(Arr::first($sorted), Arr::recursiveksort($arr['articles']));
        $this->assertEquals('articles', Arr::keyFirst($sorted));
    }
}
