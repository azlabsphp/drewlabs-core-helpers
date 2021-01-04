<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class HashingTest extends TestCase
{
    public function testHashStringFunction()
    {
        $source_string = 'Hello Test';
        $other_string = 'Hello Test';
        $key_resolver = function() {
            return 'APP_KEY';
        };
        $this->assertTrue(\drewlabs_core_hashing_hash_str_compare($other_string, \drewlabs_core_hashing_hash_str($source_string, $key_resolver), $key_resolver), 'Expects the hash result of both source_string and other_string to be equals');
    }
}