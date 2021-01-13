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

class FormatHelpersTest extends TestCase
{
    public function testFormAmount()
    {
        $result = drewlabs_core_format_amount_value(18225000.50, 1, ',');
        var_dump($result);
        $this->assertTrue(true, 'Expect Test to complete successfully');
    }
}
