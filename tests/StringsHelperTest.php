<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class StringsHelperTest extends TestCase
{

    public function testStringAfterFunction()
    {
        $haystack = 'DonalTrumpVsJoeBaiden';

        $this->assertEquals(\drewlabs_core_strings_after('Vs', $haystack), 'JoeBaiden', 'Expect strings afert Vs to be JoeBaiden');
    }

    public function testStringsToCamelCaseFunction()
    {
        $source = 'test_char_variable';

        $this->assertEquals(\drewlabs_core_strings_as_camel_case($source, true), 'TestCharVariable', 'Expect the transformed string to equals TestCharVariable');
    } //

    public function testStringsToSnakeCaseFunction()
    {
        $source = 'TestChar_Variable ';

        $this->assertEquals(\drewlabs_core_strings_as_snake_case($source), 'test_char_variable', 'Expect the transformed string to equals test_char_variable');
    }
}
