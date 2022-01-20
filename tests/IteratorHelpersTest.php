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

use Drewlabs\Core\Helpers\Iter;
use PHPUnit\Framework\TestCase;

class IteratorHelpersTest extends TestCase
{
    public function testIteratorFilterMethod()
    {
        $iterator = new \ArrayIterator([1, 2, 3, 4, 5, 6, 7, 8]);

        $filteredValue = drewlabs_core_iter_filter(
            $iterator,
            static function ($value, $key) {
                return 0 === $value % 2;
            }
        );
        $this->assertSame(4, iterator_count($filteredValue), 'Expect the odd numbers to equals 4');
    }

    public function testIteratorOnlyFunction()
    {
        $test_array = [
            'user' => 'admin',
            'password' => 'homestead',
            'host' => '127.0.0.1',
            'port' => 22,
        ];
        $result = iterator_to_array(drewlabs_core_iter_only(new \ArrayIterator($test_array), ['password']));
        $this->assertTrue(4 !== \count($result), 'Expect test to fail');
        $this->assertCount(1, $result, 'Assert only on item in array');
        $this->assertTrue(\in_array('password', array_keys($result), true), 'Assert password key is in result array');

        // Test filtering on value

        $result = iterator_to_array(drewlabs_core_iter_only(
            new \ArrayIterator($test_array),
            ['127.0.0.1', 'user'],
            false
        ));
        $result = drewlabs_core_array_only($test_array, ['127.0.0.1', 'admin'], false);
        $this->assertTrue(4 !== \count($result), 'Expect test to fail');
        $this->assertCount(2, $result, 'Assert only on item in array');
        $this->assertTrue(\in_array('host', array_keys($result), true), 'Assert password key is in result array');
        $this->assertTrue(\in_array('user', array_keys($result), true), 'Test for fail case');
    }

    public function testIterExcept()
    {
        $test_array = [
            'user' => 'admin',
            'password' => 'homestead',
            'host' => '127.0.0.1',
            'port' => 22,
        ];
        $result = iterator_to_array(Iter::except(new \ArrayIterator($test_array), ['password']));
        $this->assertTrue(4 !== \count($result));
        $this->assertCount(3, $result, 'Expect the result of the except function to contain 3 elements');
        $this->assertTrue(!\in_array('password', array_keys($result), true), 'Assert password key is not in result array');
        // Test filtering on value

        $result = iterator_to_array(Iter::except(
            new \ArrayIterator($test_array),
            ['127.0.0.1', 'admin'],
            false
        ));
        $this->assertTrue(4 !== \count($result));
        $this->assertCount(2, $result);
        $this->assertTrue(!\in_array('host', array_keys($result), true));
        $this->assertTrue(!\in_array('user', array_keys($result), true));
    }
}
