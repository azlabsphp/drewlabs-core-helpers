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

class StrTest extends TestCase
{
    public function testStringAfterFunction()
    {
        $haystack = 'DonalTrumpVsJoeBaiden';

        $this->assertSame(drewlabs_core_strings_after('Vs', $haystack), 'JoeBaiden', 'Expect strings afert Vs to be JoeBaiden');
    }

    public function testStringsToCamelCaseFunction()
    {
        $source = 'test_char_variable';

        $this->assertSame(drewlabs_core_strings_as_camel_case($source, true), 'TestCharVariable', 'Expect the transformed string to equals TestCharVariable');
    }

    public function testStringsToSnakeCaseFunction()
    {
        $source = 'TestChar_Variable ';

        $this->assertSame(drewlabs_core_strings_as_snake_case($source), 'test_char_variable', 'Expect the transformed string to equals test_char_variable');
    }

    public function test_str_hash()
    {
        $hash = Str::hash('Hello World!', 'secretkey');
        $this->assertTrue(Str::hequals($hash, Str::hash('Hello World!', 'secretkey')));
    }

    public function test_str_json()
    {
        $json = Str::stringify([
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
        ]);
        $json2 = Str::stringify([
            [
                'weight' => '30pd',
                'name' => 'Banana'
            ],
            [
                'weight' => '10pd',
                'name' => 'Orange',
            ],
            [
                'name' => 'Apple',
                'weight' => '20pd',
            ]
        ]);
        $this->assertIsString($json);
        $this->assertEquals($json, $json2);
    }
}
