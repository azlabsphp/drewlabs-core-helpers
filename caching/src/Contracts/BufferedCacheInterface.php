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

namespace Drewlabs\Caching\Contracts;

interface BufferedCacheInterface extends CacheInterface
{
    /**
     * Set the `size` state of the cache instance.
     *
     * @return void
     */
    public function setCapacity(int $capacity);

    /**
     * Returns the current state of the cache size.
     *
     * @return int
     */
    public function getCapacity();
}
