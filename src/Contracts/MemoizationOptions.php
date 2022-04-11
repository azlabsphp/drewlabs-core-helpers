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

namespace Drewlabs\Core\Helpers\Contracts;

interface MemoizationOptions
{
    /**
     * Returns the cache object to be used.
     *
     * @return object
     *
     * ```php
     * <?php
     * class Cache
     * {
     *      // Returns a cached item from cache if it exists
     *      public function get($key): mixed;
     *
     *      // Set new cache value
     *      public function set($key, $value);
     *
     *      // Removes the matching cached value from cache
     *      public function remove($key);
     *
     *      // Clears the cache by removing cached values
     *      public function clear();
     * }
     * ```
     */
    public function useCache();

    /**
     * Cache size to be used. Default for the default LRU Cache is 16.
     */
    public function cacheSize(): int;
}
