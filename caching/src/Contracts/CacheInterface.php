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

interface CacheInterface
{
    /**
     * Returns a cached item from cache if it exists.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Set new cache value.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Removes the matching cached value from cache.
     *
     * @param mixed $key
     *
     * @return void
     */
    public function remove($key);

    /**
     * Clears the cache by removing cached values.
     *
     * @return void
     */
    public function clear();
}
