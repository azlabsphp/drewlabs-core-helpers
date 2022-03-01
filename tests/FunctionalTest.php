<?php


namespace Drewlabs\Core\Helpers\Tests;

use Drewlabs\Core\Helpers\Functional;
use PHPUnit\Framework\TestCase;
use stdClass;

class FunctionalTest extends TestCase
{

    public function test_compose()
    {
        $pipe = Functional::compose(
            function (array $haystack) {
                return array_map(function ($value) {
                    return $value * 2;
                }, $haystack);
            },
            function (array $haystack) {

                return array_reduce($haystack, function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }, 0);
            },
            function ($result) {
                return $result;
            }
        );
        $this->assertEquals(
            array_reduce(
                array_map(function ($value) {
                    return $value * 2;
                }, range(1, 10)),
                function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }
            ),
            $pipe(range(1, 10))
        );
    }

    public function test_vcompose()
    {
        $pipe = Functional::vcompose(
            function (array $haystack, $initial) {

                return array_reduce($haystack, function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }, $initial);
            },
            function ($result) {
                return $result;
            }
        );
        $this->assertEquals(
            array_reduce(
                range(1, 10),
                function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                },
                2
            ),
            $pipe(range(1, 10), 2)
        );
    }



    public function test_rcompose()
    {
        $pipe = Functional::rcompose(
            function ($result) {
                return $result;
            },
            function (array $haystack) {

                return array_reduce($haystack, function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }, 0);
            },
            function (array $haystack) {
                return array_map(function ($value) {
                    return $value * 2;
                }, $haystack);
            }
        );
        $this->assertEquals(
            array_reduce(
                array_map(function ($value) {
                    return $value * 2;
                }, range(1, 10)),
                function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }
            ),
            $pipe(range(1, 10))
        );
    }

    public function test_rvcompose()
    {
        $pipe = Functional::rvcompose(
            function ($result) {
                return $result;
            },
            function (array $haystack, $initial) {

                return array_reduce($haystack, function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                }, $initial);
            }
        );
        $this->assertEquals(
            array_reduce(
                range(1, 10),
                function ($carry, $current) {
                    $carry += $current;
                    return $carry;
                },
                2
            ),
            $pipe(range(1, 10), 2)
        );
    }

    public function test_isCallable()
    {
        $object1 = new class
        {
            public function __invoke()
            {
            }
        };

        $object2 = new class
        {
            public function __call($name, $arguments)
            {
            }
        };

        $closure = function () {
            print_r("Hello World!");
        };

        $phpStringFunc = 'strtolower';

        $this->assertTrue(Functional::isCallable([$object1]));
        $this->assertTrue(Functional::isCallable([$object2, '__call']));
        $this->assertFalse(Functional::isCallable($object2));
        $this->assertTrue(Functional::isCallable($closure));
        $this->assertTrue(Functional::isCallable($phpStringFunc));
    }

    public function test_Tap()
    {
        $class = new class extends \stdClass
        {

            private $totalCalls = 0;

            private $calledWith;

            public function __invoke($param)
            {
                ++$this->totalCalls;

                $this->calledWith = $param;
            }

            public function toBeCalledOnce()
            {
                return $this->totalCalls !== 0;
            }

            public function toBeCalledWith($value)
            {
                return $this->calledWith == $value;
            }
        };
        Functional::tap(3, $class);

        $this->assertTrue($class->toBeCalledOnce());
        $this->assertTrue($class->toBeCalledWith(3));
    }



    public function test_Tap_With_Not_String_Callable()
    {
        $class = new class extends \stdClass
        {

            private $calledWith;

            public function __invoke($param)
            {

                $this->calledWith = $param;
            }
            public function toBeCalledWith($value)
            {
                return $this->calledWith == $value;
            }
        };
        Functional::tap(function() {
            return 'Hello World!';
        }, $class);
        $this->assertTrue($class->toBeCalledWith('Hello World!'));
        Functional::tap('is_string', $class);
        $this->assertTrue($class->toBeCalledWith('is_string'));
    }

    public function test_Tap_Immutable_For_Simple_Object()
    {
        $object = new stdClass;
        $object->age = 20;
        Functional::tap($object, function($state) {
            $state->age = 23;
        });
        $this->assertEquals(20, $object->age);
    }
}
