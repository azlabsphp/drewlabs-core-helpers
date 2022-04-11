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

namespace Drewlabs\Core\Helpers\Tests\Stubs;

#[NameAttribute]
class Person implements PersonInterface
{
    use NameAware;

    public $login = 'Asmyns14';
    public $email = 'asmyns.platonnas29@gmail.com';
    private $secret = 'PassWord';
}
