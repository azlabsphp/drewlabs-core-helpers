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
        $iterator = new ArrayIterator([1,2,3,4,5,6,7,8]);

        $filteredValue = drewlabs_core_iter_filter($iterator, function($value, $key) {
            return $value % 2 === 0;
        });
        $this->assertEquals(4, iterator_count($filteredValue), 'Expect the odd numbers to equals 4');
    }
}
