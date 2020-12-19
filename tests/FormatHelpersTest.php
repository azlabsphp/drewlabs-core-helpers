<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class FormatHelpersTest extends TestCase
{
    public function testFormAmount()
    {
        $result = \drewlabs_core_format_amount_value(18225000.50, 1, ',');
        var_dump($result);
        $this->assertTrue(true, 'Expect Test to complete successfully');
    }
}
