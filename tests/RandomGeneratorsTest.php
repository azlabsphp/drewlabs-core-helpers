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

use Drewlabs\Core\Helpers\UUID;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidFactory;

class RandomGeneratorsTest extends TestCase
{
    public function testGenerateUuid()
    {
        $uuid = UUID::create();
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }

    public function testGenerateOrderedUuid()
    {
        $uuid = UUID::ordered();
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }

    public function testGenerateUuidUsinfFactory()
    {
        $uuid = UUID::createUsing(static function () {
            return (new UuidFactory())->uuid4();
        });
        $this->assertTrue((new UuidFactory())->getValidator()->validate($uuid));
    }
}
