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

use Drewlabs\Core\Helpers\Functional;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function test_compose()
    {
        $pipe = Functional::compose(
            static function (array $haystack) {
                return array_map(static function ($value) {
                    return $value * 2;
                }, $haystack);
            },
            static function (array $haystack) {
                return array_reduce($haystack, static function ($carry, $current) {
                    $carry += $current;

                    return $carry;
                }, 0);
            },
            static function ($result) {
                return $result;
            }
        );
        $this->assertSame(
            array_reduce(
                array_map(static function ($value) {
                    return $value * 2;
                }, range(1, 10)),
                static function ($carry, $current) {
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
            static function (array $haystack, $initial) {
                return array_reduce($haystack, static function ($carry, $current) {
                    $carry += $current;

                    return $carry;
                }, $initial);
            },
            static function ($result) {
                return $result;
            }
        );
        $this->assertSame(
            array_reduce(
                range(1, 10),
                static function ($carry, $current) {
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
            static function ($result) {
                return $result;
            },
            static function (array $haystack) {
                return array_reduce($haystack, static function ($carry, $current) {
                    $carry += $current;

                    return $carry;
                }, 0);
            },
            static function (array $haystack) {
                return array_map(static function ($value) {
                    return $value * 2;
                }, $haystack);
            }
        );
        $this->assertSame(
            array_reduce(
                array_map(static function ($value) {
                    return $value * 2;
                }, range(1, 10)),
                static function ($carry, $current) {
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
            static function ($result) {
                return $result;
            },
            static function (array $haystack, $initial) {
                return array_reduce($haystack, static function ($carry, $current) {
                    $carry += $current;

                    return $carry;
                }, $initial);
            }
        );
        $this->assertSame(
            array_reduce(
                range(1, 10),
                static function ($carry, $current) {
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
        $object1 = new class() {
            public function __invoke()
            {
            }
        };

        $object2 = new class() {
            public function __call($name, $arguments)
            {
            }
        };

        $closure = static function () {
            print_r('Hello World!');
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
        $class = new class() extends \stdClass {
            private $totalCalls = 0;

            private $calledWith;

            public function __invoke($param)
            {
                ++$this->totalCalls;

                $this->calledWith = $param;
            }

            public function toBeCalledOnce()
            {
                return 0 !== $this->totalCalls;
            }

            public function toBeCalledWith($value)
            {
                return $this->calledWith === $value;
            }
        };
        Functional::tap(3, $class);

        $this->assertTrue($class->toBeCalledOnce());
        $this->assertTrue($class->toBeCalledWith(3));
    }

    public function test_Tap_With_Not_String_Callable()
    {
        $class = new class() extends \stdClass {
            private $calledWith;

            public function __invoke($param)
            {
                $this->calledWith = $param;
            }

            public function toBeCalledWith($value)
            {
                return $this->calledWith === $value;
            }
        };
        Functional::tap(static function () {
            return 'Hello World!';
        }, $class);
        $this->assertTrue($class->toBeCalledWith('Hello World!'));
        Functional::tap('is_string', $class);
        $this->assertTrue($class->toBeCalledWith('is_string'));
    }

    public function test_Tap_Immutable_For_Simple_Object()
    {
        $object = new \stdClass();
        $object->age = 20;
        Functional::tap($object, static function ($state) {
            $state->age = 23;
        });
        $this->assertSame(20, $object->age);
    }

    public function test_memoize_function()
    {
        $closure = new class() {
            private $params;

            private $count = 0;

            public function __invoke(...$args)
            {
                ++$this->count;
                $this->params = $args;

                return array_reduce($args[0], static function ($carry, $current) {
                    $carry += $current;

                    return $carry;
                }, 0);
            }

            public function callNTimes(int $times)
            {
                return $this->count === $times;
            }

            public function calledWith(...$args)
            {
                return $this->params === $args;
            }
        };

        $memoized = Functional::memoize($closure, 2);
        $memoized([1, 2, 3, 4]); // Call memoized function with [1, 2, 3, 4]
        // Make sure that the memoized function is called at least with arguments [1,2,3,4]
        $this->assertTrue(true === $memoized->calledWith([1, 2, 3, 4]));
        $memoized([1, 2, 3, 4]); // Call memoized function with [1, 2, 3, 4]
        $memoized([1, 2, 3, 4]); // Call memoized function with [1, 2, 3, 4]
        $this->assertTrue(true === $memoized->callNTimes(1));
        $memoized([1, 2, 3]); // Call memoized function with [1, 2, 3]
        $this->assertTrue(true === $memoized->callNTimes(2));
        $memoized([1, 2, 3]); // Call memoized function with [1, 2, 3, 4]
        $this->assertTrue(true === $memoized->callNTimes(2));
        $memoized->remove([1, 2, 3]); // Call memoized function with [1, 2, 3, 4]
        $memoized([1, 2, 3]); // Call memoized function with [1, 2, 3, 4]
        // Expect the method to be called again if the argument list was removed
        // from the cache
        $this->assertTrue(true === $memoized->callNTimes(3));
        $memoized([1, 2, 3]); // Call memoized function with [1, 2, 3, 4]
        $this->assertTrue(true === $memoized->callNTimes(3));
        $memoized([1, 2]);
        $memoized([1]);
        $memoized([1]);
        $memoized([1]);
        $memoized([1]);
    }
}
