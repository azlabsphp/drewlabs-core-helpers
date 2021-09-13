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

use ArrayIterator;
use PHPUnit\Framework\TestCase;

class IteratorHelpersTest extends TestCase
{
    public function testIteratorFilterMethod()
    {
        $iterator = new ArrayIterator([1, 2, 3, 4, 5, 6, 7, 8]);

        $filteredValue = drewlabs_core_iter_filter($iterator, function ($value, $key) {
            return $value % 2 === 0;
        });
        $this->assertEquals(4, iterator_count($filteredValue), 'Expect the odd numbers to equals 4');
    }

    public function  testIteratorOnlyFunction()
    {

        $test_array = [
            'user' => 'admin',
            'password' => 'homestead',
            'host' => '127.0.0.1',
            'port' => 22
        ];
        $result = iterator_to_array(drewlabs_core_iter_only(new ArrayIterator($test_array), ['password']));
        $this->assertTrue(count($result) !== 4, 'Expect test to fail');
        $this->assertEquals(1, count($result), 'Assert only on item in array');
        $this->assertTrue(in_array('password', array_keys($result)), 'Assert password key is in result array');

        // Test filtering on value

        $result = iterator_to_array(drewlabs_core_iter_only(
            new ArrayIterator($test_array),
            ['127.0.0.1', 'user'],
            false
        ));
        $result = drewlabs_core_array_only($test_array, ['127.0.0.1', 'admin'], false);
        $this->assertTrue(count($result) !== 4, 'Expect test to fail');
        $this->assertEquals(1, count($result), 'Assert only on item in array');
        $this->assertTrue(in_array('host', array_keys($result)), 'Assert password key is in result array');
        $this->assertTrue(!in_array('user', array_keys($result)), 'Test for fail case');
    }
}
