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

use Drewlabs\Core\Helpers\Str;
use PHPUnit\Framework\TestCase;

class HashingTest extends TestCase
{
    public function testHashStringFunction()
    {
        $source = 'Hello Test';
        $match = 'Hello Test';
        $resolver = static function () {
            return 'APP_KEY';
        };
        $this->assertTrue(Str::hequals(Str::hash($source, $resolver), Str::hash($match, $resolver)), 'Expects the hash result of both source_string and other_string to be equals');
    }
}
