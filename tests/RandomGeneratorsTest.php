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
use Ramsey\Uuid\UuidFactory;

class RandomGeneratorsTest extends TestCase
{
    public function testGenerateUuid()
    {
        $uuid = drewlabs_core_random_guid();
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }

    public function testGenerateOrderedUuid()
    {
        $uuid = drewlabs_core_random_ordered_uuid();
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }

    public function testGenerateUuidUsinfFactory()
    {
        $uuid = drewlabs_core_random_create_uuids_using(static function () {
            return (new UuidFactory())->uuid4();
        });
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }
}
