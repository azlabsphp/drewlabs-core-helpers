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
}
